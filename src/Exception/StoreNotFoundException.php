<?php

namespace CodeCloud\Bundle\ShopifyBundle\Exception;

class StoreNotFoundException extends \InvalidArgumentException
{
    public function __construct($storeName)
    {
        parent::__construct(sprintf('Store "%s" does not exist', $storeName));
    }
}
