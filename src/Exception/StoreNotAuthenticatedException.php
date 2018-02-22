<?php

namespace CodeCloud\Bundle\ShopifyBundle\Exception;

class StoreNotAuthenticatedException extends \RuntimeException
{
    public function __construct($storeName)
    {
        parent::__construct(sprintf('Store "%s" is not authenticated', $storeName));
    }
}
