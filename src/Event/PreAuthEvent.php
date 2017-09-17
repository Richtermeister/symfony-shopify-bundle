<?php

namespace CodeCloud\Bundle\ShopifyBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class PreAuthEvent extends Event
{
    const NAME = 'codecloud.shopify.pre_auth';

    /**
     * @var string
     */
    private $shop;

    /**
     * @var Response
     */
    private $response;

    /**
     * @param string $shop
     */
    public function __construct(string $shop)
    {
        $this->shop = $shop;
    }

    /**
     * @return string
     */
    public function getShop(): string
    {
        return $this->shop;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
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
