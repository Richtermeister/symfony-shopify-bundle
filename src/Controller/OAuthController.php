<?php

namespace CodeCloud\Bundle\ShopifyBundle\Controller;

use CodeCloud\Bundle\ShopifyBundle\Event\PostAuthEvent;
use CodeCloud\Bundle\ShopifyBundle\Event\PreAuthEvent;
use CodeCloud\Bundle\ShopifyBundle\Exception\InsufficientScopeException;
use CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreManagerInterface;
use CodeCloud\Bundle\ShopifyBundle\Security\HmacSignature;
use GuzzleHttp\ClientInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Handles the OAuth handshake with Shopify.
 *
 * @see https://help.shopify.com/api/getting-started/authentication/oauth
 */
class OAuthController
{
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var array
     */
    private $config;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ShopifyStoreManagerInterface
     */
    private $stores;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var HmacSignature
     */
    private $hmacSignature;

    /**
     * @param UrlGeneratorInterface $router
     * @param array $config
     * @param ClientInterface $client
     * @param ShopifyStoreManagerInterface $stores
     * @param EventDispatcherInterface $dispatcher
     * @param HmacSignature $hmacSignature
     */
    public function __construct(
        UrlGeneratorInterface $router,
        array $config,
        ClientInterface $client,
        ShopifyStoreManagerInterface $stores,
        EventDispatcherInterface $dispatcher,
        HmacSignature $hmacSignature
    ) {
        $this->router = $router;
        $this->client = $client;
        $this->stores = $stores;
        $this->config = (new OptionsResolver())
            ->setRequired(['api_key', 'shared_secret', 'scope', 'redirect_route'])
            ->resolve($config)
        ;
        $this->dispatcher = $dispatcher;
        $this->hmacSignature = $hmacSignature;
    }

    /**
     * Handles initial auth request from Shopify.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function auth(Request $request)
    {
        if (!$storeName = $request->get('shop')) {
            throw new BadRequestHttpException('Request is missing required parameter "shop".');
        }

        if ($response = $this->dispatcher->dispatch(
            PreAuthEvent::NAME,
            new PreAuthEvent($storeName))->getResponse()
        ) {
            return $response;
        }

        $verifyUrl = $this->router->generate('codecloud_shopify_verify', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $verifyUrl = str_replace("http://", "https://", $verifyUrl);
        $nonce = uniqid();

        $this->stores->preAuthenticateStore($storeName, $nonce);

        $params = [
            'client_id'    => $this->config['api_key'],
            'scope'        => $this->config['scope'],
            'redirect_uri' => $verifyUrl,
            'state'        => $nonce,
        ];

        $shopifyEndpoint = 'https://%s/admin/oauth/authorize?%s';
        $url = sprintf($shopifyEndpoint, $storeName, http_build_query($params));

        return new RedirectResponse($url);
    }

    /**
     * Handles auth verification callback from Shopify.
     *
     * @param Request $request
     * @return string
     */
    public function verify(Request $request)
    {
        $authCode  = $request->get('code');
        $storeName = $request->get('shop');
        $nonce     = $request->get('state');
        $hmac      = $request->get('hmac');

        // todo validate store name
        // todo leverage options resolver?

        if (!$authCode || !$storeName || !$nonce || !$hmac) {
            throw new BadRequestHttpException('Request is missing one or more of required parameters: "code", "shop", "state", "hmac".');
        }

        if (!$this->hmacSignature->isValid($hmac, $request->query->all())) {
            throw new BadRequestHttpException('Invalid HMAC Signature');
        }

        $params = [
            'body' => \GuzzleHttp\json_encode([
                'client_id'     => $this->config['api_key'],
                'client_secret' => $this->config['shared_secret'],
                'code'          => $authCode
            ]),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        // todo this can fail - 400
        $response = $this->client->request('POST', 'https://' . $storeName . '/admin/oauth/access_token', $params);
        $responseJson = \GuzzleHttp\json_decode($response->getBody(), true);

        if ($responseJson['scope'] != $this->config['scope']) {
            throw new InsufficientScopeException($this->config['scope'], $responseJson['scope']);
        }

        $accessToken = $responseJson['access_token'];
        $this->stores->authenticateStore($storeName, $accessToken, $nonce);

        if ($response = $this->dispatcher->dispatch(
            PostAuthEvent::NAME,
            new PostAuthEvent($storeName, $accessToken))->getResponse()
        ) {
            return $response;
        }

        return new RedirectResponse(
            $this->router->generate($this->config['redirect_route'])
        );
    }
}
