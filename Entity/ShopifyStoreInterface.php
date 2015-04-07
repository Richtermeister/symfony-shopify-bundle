<?php
namespace CodeCloud\Bundle\ShopifyBundle\Entity;

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