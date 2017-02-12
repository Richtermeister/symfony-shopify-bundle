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
     * @var string
     */
    private $endpointClasses = [];

    /**
     * @param ShopifyStoreManagerInterface $storeManager
     * @param HttpClientFactoryInterface $httpClientFactory
     * @param array $endpointClasses
     */
    public function __construct(
        ShopifyStoreManagerInterface $storeManager,
        HttpClientFactoryInterface $httpClientFactory,
        array $endpointClasses
    ) {
        $this->storeManager = $storeManager;
        $this->httpClientFactory = $httpClientFactory;
        $this->endpointClasses = $endpointClasses;
    }

    /**
     * @param string $storeName
     * @return ShopifyApi
     */
    public function getForStore($storeName)
    {
        if (!$store = $this->storeManager->findStoreByName($storeName)) {
            throw new \InvalidArgumentException(sprintf('Store %s does not exist', $storeName));
        }

        $client = $this->httpClientFactory->createHttpClient($store);

        return new ShopifyApi($client, $this->endpointClasses);
    }
}
