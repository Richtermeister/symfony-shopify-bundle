<?php

namespace CodeCloud\Bundle\ShopifyBundle\Api;

use CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreInterface;
use GuzzleHttp\Client;

/**
 * Creates authenticated clients for public and private apps.
 */
class HttpClientFactory implements HttpClientFactoryInterface
{
    public function createHttpClient(ShopifyStoreInterface $shopifyStore)
    {
        $options = [
            'base_uri' => 'https://' . $shopifyStore->getStoreName(),
        ];

        $credentials = $shopifyStore->getCredentials();

        switch (true) {
            case  $credentials instanceof PublicAppCredentials:
                $options['headers'] = [
                    'X-Shopify-Access-Token' => $credentials->getAccessToken(),
                ];
                break;
            case $credentials instanceof PrivateAppCredentials:
                $options['auth'] = [
                    $credentials->getApiKey(),
                    $credentials->getPassword(),
                ];
                break;
            default:
                throw new \RuntimeException('Invalid credentials given');
        }

        return new Client($options);
    }
}
