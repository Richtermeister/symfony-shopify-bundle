<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class ShopEndpoint extends AbstractEndpoint
{
    /**
     * @return GenericResource
     */
    public function findOne()
    {
        $response = $this->send(new GetJson('/admin/shop.json'));
        return $this->createEntity($response->get('shop'));
    }
}
