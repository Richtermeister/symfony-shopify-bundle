<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Response;

use Psr\Http\Message\ResponseInterface as PsrResponse;

interface ResponseInterface
{
    /**
     * @return bool
     */
    public function successful();

    /**
     * Get the body of the response.
     * If item is specified, this can be used to drill down into the response object and retrieve specific items within it
     * @param string $item
     * @param mixed $default
     * @return mixed
     */
    public function get($item = null, $default = null);

    /**
     * @return PsrResponse
     */
    public function getHttpResponse();
}
