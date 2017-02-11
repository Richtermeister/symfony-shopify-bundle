<?php

namespace CodeCloud\Bundle\ShopifyBundle\Controller;

use CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreManagerInterface;
use GuzzleHttp\ClientInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * @param UrlGeneratorInterface $router
     * @param array $config
     * @param ClientInterface $client
     * @param ShopifyStoreManagerInterface $stores
     */
    public function __construct(
        UrlGeneratorInterface $router,
        array $config,
        ClientInterface $client,
        ShopifyStoreManagerInterface $stores
    ) {
        $this->router = $router;
        $this->client = $client;
        $this->stores = $stores;
        $this->config = (new OptionsResolver())
            ->setRequired(['api_key', 'shared_secret', 'scope'])
            ->resolve($config)
        ;
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

        $verifyUrl = $this->router->generate('code_cloud_shopify_verify', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $verifyUrl = str_replace("http", "https", $verifyUrl);

        $params = [
            'client_id'    => $this->config['api_key'],
            'scope'        => $this->config['scope'],
            'redirect_uri' => $verifyUrl,
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
        $authCode = $request->get('code');
        $storeName = $request->get('shop');

        if (!$authCode || !$storeName) {
            throw new BadRequestHttpException('Request is missing required parameters: "code", "shop".');
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

        $response = $this->client->request('POST', 'https://' . $storeName . '/admin/oauth/access_token', $params);
        $responseJson = \GuzzleHttp\json_decode($response->getBody(), true);

        $accessToken = $responseJson['access_token'];
        $this->stores->authenticateStore($storeName, $accessToken);

        return new RedirectResponse(
            $this->router->generate('admin_dash', $request->query->all())
        );
    }
}
