<?php

namespace CodeCloud\Bundle\ShopifyBundle\Event;

use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;
use CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreInterface;
use Symfony\Component\EventDispatcher\Event;

class WebhookEvent extends Event
{
    const NAME = 'codecloud_shopify_webhook';

    /**
     * @var string
     */
    private $topic;

    /**
     * @var ShopifyStoreInterface
     */
    private $store;

    /**
     * @var GenericResource
     */
    private $resource;

    /**
     * @param string $topic
     * @param ShopifyStoreInterface $store
     * @param GenericResource $resource
     */
    public function __construct($topic, ShopifyStoreInterface $store, GenericResource $resource)
    {
        $this->topic = $topic;
        $this->store = $store;
        $this->resource = $resource;
    }

    /**
     * @return string
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * @return ShopifyStoreInterface
     */
    public function getStore(): ShopifyStoreInterface
    {
        return $this->store;
    }

    /**
     * @return GenericResource
     */
    public function getResource(): GenericResource
    {
        return $this->resource;
    }
}
