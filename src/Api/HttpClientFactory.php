<?php

namespace CodeCloud\Bundle\ShopifyBundle\Api;

use GuzzleHttp\Client;

class HttpClientFactory implements HttpClientFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createHttpClient()
    {
        $shopName = 'alastin.myshopify.com';
        $accessToken = 'f18432a4441a9e45b2287d5661e32c9f';

        return new Client([
            'base_uri' => 'https://' . $shopName,
            'headers' => [
                'X-Shopify-Access-Token' => $accessToken,
            ],
        ]);
    }
}
