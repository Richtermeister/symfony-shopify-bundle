<?php
namespace CodeCloud\Bundle\ShopifyBundle\Entity;

interface ShopifyStoreRepositoryInterface
{
	/**
	 * @param string $shopName
	 * @return ShopifyStoreInterface
	 */
	public function findOneByShopName($shopName);
}