<?php

namespace CodeCloud\Bundle\ShopifyBundle\Api;

use CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreInterface;
use GuzzleHttp\ClientInterface;

interface HttpClientFactoryInterface
{
    /**
     * @param \CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreInterface $shopifyStore
     * @return ClientInterface
     */
    public function createPublicAppHttpClient(ShopifyStoreInterface $shopifyStore);

    /**
     * @param string $storeName
     * @param string $apiKey
     * @param string $password
     * @return ClientInterface
     */
    public function createPrivateAppHttpClient($storeName, $apiKey, $password);
}
