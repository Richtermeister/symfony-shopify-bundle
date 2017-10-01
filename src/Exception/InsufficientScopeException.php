<?php

namespace CodeCloud\Bundle\ShopifyBundle\Exception;

/**
 * Thrown when a shop authorization granted different scope than was requested.
 */
class InsufficientScopeException extends \RuntimeException
{
    /**
     * @param string $requestedScope
     * @param string $grantedScope
     */
    public function __construct(string $requestedScope, string $grantedScope)
    {
        parent::__construct(
            sprintf(
                'Insufficient scope. Requested: "%s", granted: "%s".',
                $requestedScope,
                $grantedScope
            )
        );
    }
}
