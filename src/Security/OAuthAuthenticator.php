<?php

namespace CodeCloud\Bundle\ShopifyBundle\Security;

use CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class OAuthAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var HmacSignature
     */
    private $signatureVerifier;

    /**
     * @var ShopifyStoreManagerInterface
     */
    private $shops;

    /**
     * @param HmacSignature $signatureVerifier
     * @param \CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreManagerInterface $shops
     */
    public function __construct(
        HmacSignature $signatureVerifier,
        ShopifyStoreManagerInterface $shops
    ) {
        $this->signatureVerifier = $signatureVerifier;
        $this->shops = $shops;
    }

    public function getCredentials(Request $request)
    {
        foreach (['shop', 'hmac', 'timestamp'] as $param) {
            if (!$request->query->has($param)) {
                return null;
            }
        }

        return $request->query->all();
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $this->shops->findStoreByName($credentials['shop']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if (! $this->signatureVerifier->isValid($credentials['hmac'], $credentials)) {
            throw new BadCredentialsException('Invalid signature');
        }

        return $user->getUsername() == $credentials['shop'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response("API Authentication Failed.", 403);
    }

    public function supportsRememberMe()
    {
        // TODO: Implement supportsRememberMe() method.
        return false;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new Response('This area can only be accessed via Shopify');
    }
}
