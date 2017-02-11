<?php
namespace CodeCloud\Bundle\ShopifyBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

interface ShopifyStoreInterface extends UserInterface
{
	/**
	 * @return string
	 */
	public function getShopName();

	/**
	 * @return string
	 */
	public function getAccessToken();
}