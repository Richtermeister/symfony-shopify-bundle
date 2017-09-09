<?php

namespace CodeCloud\Bundle\ShopifyBundle\Event;

use CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreInterface;
use Symfony\Component\EventDispatcher\Event;

class StoreEvent extends Event
{
    const INSTALLED = 'codecloud_shopify_store_installed';

    /**
     * @var ShopifyStoreInterface
     */
    private $store;

    /**
     * @param ShopifyStoreInterface $store
     */
    public function __construct(ShopifyStoreInterface $store)
    {
        $this->store = $store;
    }

    /**
     * @return ShopifyStoreInterface
     */
    public function getStore(): ShopifyStoreInterface
    {
        return $this->store;
    }
}
