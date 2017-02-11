<?php

namespace CodeCloud\Bundle\ShopifyBundle\Api;

use GuzzleHttp\ClientInterface;

interface HttpClientFactoryInterface
{
    /**
     * @return ClientInterface
     */
    public function createHttpClient();
}
