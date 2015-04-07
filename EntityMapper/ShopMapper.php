<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class ShopMapper extends EntityMapper
{
	/**
	 * @return GenericEntity
	 */
	public function findOne()
	{
		$response = $this->send(new GetJson('/admin/shop.json'));
		return $this->createEntity($response->get('shop'));
	}
}