<?php

namespace CodeCloud\Bundle\ShopifyBundle\Api;

use CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreManagerInterface;

/**
 * Creates ShopifyApi instances.
 */
class ShopifyApiFactory
{
    /**
     * @var ShopifyStoreManagerInterface
     */
    private $storeManager;

    /**
     * @var HttpClientFactoryInterface
     */
    private $httpClientFactory;

    /**
     * @param ShopifyStoreManagerInterface $storeManager
     * @param HttpClientFactoryInterface $httpClientFactory
     */
    public function __construct(
        ShopifyStoreManagerInterface $storeManager,
        HttpClientFactoryInterface $httpClientFactory
    ) {
        $this->storeManager = $storeManager;
        $this->httpClientFactory = $httpClientFactory;
    }

    /**
     * @param string $storeName
     * @return ShopifyApi
     */
    public function getForStore($storeName)
    {
        $accessToken = $this->storeManager->getAccessToken($storeName);
        $client = $this->httpClientFactory->createHttpClient($storeName, new PublicAppCredentials($accessToken));

        return new ShopifyApi($client);
    }
}
