<?php
namespace CodeCloud\Bundle\ShopifyBundle\Twig\Extension;

use CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreInterface;
use CodeCloud\Bundle\ShopifyBundle\Security\HmacSignature;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ShopifyStore extends \Twig_Extension
{
    /**
     * @var HmacSignature
     */
    private $hmac;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param HmacSignature $hmac
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(HmacSignature $hmac, TokenStorageInterface $tokenStorage)
    {
        $this->hmac = $hmac;
        $this->tokenStorage = $tokenStorage;
    }

    /**
	 * @return array
	 */
	public function getFunctions()
    {
		return [
			new \Twig_SimpleFunction('embedded_link', [$this, 'embeddedLink']),
            new \Twig_SimpleFunction('shopify_store', [$this, 'shopifyStore']),
		];
	}

	public function embeddedLink($storeName, $uri, $uriParams = [])
    {
        $authParams = $this->hmac->generateParams($storeName);

        return '/embedded/' . $uri . '?' . http_build_query(
            array_merge($authParams, $uriParams)
        );
    }

    public function shopifyStore()
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return null;
        }

        $user = $token->getUser();

        if (!$user instanceof ShopifyStoreInterface) {
            return null;
        }

        return $user;
    }
}
