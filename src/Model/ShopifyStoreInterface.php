<?php
namespace CodeCloud\Bundle\ShopifyBundle\Model;

use CodeCloud\Bundle\ShopifyBundle\Api\PrivateAppCredentials;
use CodeCloud\Bundle\ShopifyBundle\Api\PublicAppCredentials;
use Symfony\Component\Security\Core\User\UserInterface;

interface ShopifyStoreInterface extends UserInterface
{
	/**
	 * @return string
	 */
	public function getShopName();

	/**
	 * @return PrivateAppCredentials|PublicAppCredentials
	 */
	public function getCredentials();
}
