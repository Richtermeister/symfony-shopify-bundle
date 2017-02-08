<?php
namespace CodeCloud\Bundle\ShopifyBundle\Entity;

interface ShopifyStoreRepositoryInterface
{
	/**
	 * @param string $shopName
	 * @return ShopifyStoreInterface
	 */
	public function findOneByShopName($shopName);

    /**
     * @param string $shopName
     * @param string $accessToken
     * @return ShopifyStoreInterface
     */
    public function authenticateStore($shopName, $accessToken);
}