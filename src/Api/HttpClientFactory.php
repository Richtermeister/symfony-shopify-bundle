<?php

namespace CodeCloud\Bundle\ShopifyBundle\Api;

use CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreInterface;
use GuzzleHttp\Client;

class HttpClientFactory implements HttpClientFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createPublicAppHttpClient(ShopifyStoreInterface $shopifyStore)
    {
        $shopName = 'alastin.myshopify.com';
        $accessToken = 'f18432a4441a9e45b2287d5661e32c9f';

        return new Client([
            'base_uri' => 'https://' . $shopifyStore->getShopName(),
            'headers' => [
                'X-Shopify-Access-Token' => $shopifyStore->getAccessToken(),
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function createPrivateAppHttpClient($storeName, $apiKey, $password)
    {
        return new Client([
            'base_uri' => 'https://' . $storeName,
            'auth' => [
                $apiKey,
                $password,
            ],
        ]);
    }
}
