<?php

namespace CodeCloud\Bundle\ShopifyBundle\Api\Response;

use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface as PsrResponse;

class ErrorResponse implements ResponseInterface
{
    /**
     * @var PsrResponse
     */
    private $response;

    /**
     * @var ClientException
     */
    private $exception;

    /**
     * @param PsrResponse $response
     * @param $exception
     */
    public function __construct(PsrResponse $response, ClientException $exception)
    {
        $this->response  = $response;
        $this->exception = $exception;
    }

    /**
     * @param null $item
     * @param null $default
     * @return mixed
     */
    public function get($item = null, $default = null)
    {
        return 'An error occurred while processing the request.';
    }

    /**
     * @return PsrResponse
     */
    public function getHttpResponse()
    {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function successful()
    {
        return false;
    }
}
