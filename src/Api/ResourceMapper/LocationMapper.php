<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;

class LocationMapper extends ResourceMapper
{
	/**
	 * @return array|GenericEntity[]
	 */
	public function findAll()
	{
		$request = new GetJson('/admin/locations.json');
		$response = $this->send($request);
		return $this->createCollection($response->get('locations'));
	}

	/**
	 * @param int $locationId
	 * @return GenericEntity
	 */
	public function findOne($locationId)
	{
		$request = new GetJson('/admin/locations/' . $locationId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('location'));
	}
}