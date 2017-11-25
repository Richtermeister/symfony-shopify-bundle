<?php
namespace CodeCloud\Bundle\ShopifyBundle\Twig\Extension;

use CodeCloud\Bundle\ShopifyBundle\Security\HmacSignature;

class ShopifyStore extends \Twig_Extension
{
    /**
     * @var HmacSignature
     */
    private $hmac;

    /**
     * @param HmacSignature $hmac
     */
    public function __construct(HmacSignature $hmac)
    {
        $this->hmac = $hmac;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('embedded_link', [$this, 'embeddedLink']),
        ];
    }

    public function embeddedLink($storeName, $uri, $uriParams = [])
    {
        $authParams = $this->hmac->generateParams($storeName);

        return '/embedded/' . $uri . '?' . http_build_query(
            array_merge($authParams, $uriParams)
        );
    }
}
