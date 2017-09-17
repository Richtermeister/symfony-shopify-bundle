<?php

namespace CodeCloud\Bundle\ShopifyBundle\Service;

/**
 * Implemented by classes which can interact with Shopify Webhooks.
 */
interface WebhookCreatorInterface
{
    /**
     * Creates webhooks in Shopify.
     *
     * @param string $storeName
     * @param array $topics
     */
    public function createWebhooks(string $storeName, array $topics);

    /**
     * Lists existing webhooks in Shopify.
     *
     * @param string $storeName
     * @return array
     */
    public function listWebhooks(string $storeName);

    /**
     * Deletes all webhooks in Shopify.
     *
     * @param string $storeName
     */
    public function deleteAllWebhooks(string $storeName);
}
