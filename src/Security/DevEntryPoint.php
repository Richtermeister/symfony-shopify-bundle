<?php

namespace CodeCloud\Bundle\ShopifyBundle\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class DevEntryPoint implements AuthenticationEntryPointInterface
{
    /**
     * @var GuardAuthenticatorHandler
     */
    private $authHandler;

    /**
     * @var string
     */
    private $storeName;

    /**
     * @var string
     */
    private $firewallName;

    public function __construct(GuardAuthenticatorHandler $authHandler, $storeName, $firewallName)
    {
        $this->authHandler = $authHandler;
        $this->storeName = $storeName;
        $this->firewallName = $firewallName;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $user = new ShopifyAdminUser($this->storeName, ["ROLE_SHOPIFY_ADMIN"]);

        $token = new PostAuthenticationToken(
            $user,
            $this->firewallName,
            $user->getRoles()
        );

        $this->authHandler->authenticateWithToken($token, $request, $this->firewallName);

        return new RedirectResponse($request->getUri());
    }
}
