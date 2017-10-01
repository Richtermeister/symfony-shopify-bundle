<?php

namespace CodeCloud\Bundle\ShopifyBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class PostAuthEvent extends Event
{
    const NAME = 'codecloud.shopify.post_auth';

    /**
     * @var string
     */
    private $shop;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var Response
     */
    private $response;

    /**
     * @param string $shop
     * @param string $accessToken
     */
    public function __construct(string $shop, string $accessToken)
    {
        $this->shop = $shop;
        $this->accessToken = $accessToken;
    }

    /**
     * @return string
     */
    public function getShop(): string
    {
        return $this->shop;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return Response
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * @param Response $response
     * @return $this
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;

        return $this;
    }
}
