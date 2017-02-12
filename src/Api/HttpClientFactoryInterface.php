<?php

namespace CodeCloud\Bundle\ShopifyBundle\Api;

use CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreInterface;
use GuzzleHttp\ClientInterface;

/**
 * Interface implemented by classes which create a Http Client
 * which is authenticated to access the specified store.
 */
interface HttpClientFactoryInterface
{
    /**
     * @param ShopifyStoreInterface $shopifyStore
     * @return ClientInterface
     */
    public function createHttpClient(ShopifyStoreInterface $shopifyStore);
}
