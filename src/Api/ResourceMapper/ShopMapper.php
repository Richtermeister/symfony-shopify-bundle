<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class ShopMapper extends ResourceMapper
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