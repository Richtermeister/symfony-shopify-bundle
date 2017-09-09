<?php
namespace CodeCloud\Bundle\ShopifyBundle\Model;

interface ShopifyStoreManagerInterface
{
    /**
     * @param string $storeName
     * @return ShopifyStoreInterface
     */
    public function findStoreByName($storeName);

    /**
     * @param string $storeName
     * @param string $accessToken
     */
    public function authenticateStore($storeName, $accessToken);
}
