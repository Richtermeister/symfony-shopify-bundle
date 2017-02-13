<?php
namespace CodeCloud\Bundle\ShopifyBundle\Model;

use CodeCloud\Bundle\ShopifyBundle\Api\PrivateAppCredentials;
use CodeCloud\Bundle\ShopifyBundle\Api\PublicAppCredentials;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface implemented by classes which represent a Shopify store.
 */
interface ShopifyStoreInterface extends UserInterface
{
	/**
	 * @return string
	 */
	public function getStoreName();

	/**
	 * @return PrivateAppCredentials|PublicAppCredentials
	 */
	public function getCredentials();
}
