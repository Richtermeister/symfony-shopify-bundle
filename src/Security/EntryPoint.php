<?php

namespace CodeCloud\Bundle\ShopifyBundle\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * Restarts the Shopify OAuth flow, or prompts user to access the app via Shopify admin.
 */
class EntryPoint implements AuthenticationEntryPointInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        if (!$request->get('shop')) {
            return new Response('Your session has expired. Please access the app via Shopify Admin again.');
        }

        return new RedirectResponse(
            $this->urlGenerator->generate('codecloud_shopify_auth', [
                'shop' => $request->get('shop'),
            ])
        );
    }
}
