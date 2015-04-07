<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class CarrierServiceMapper extends EntityMapper
{
	/**
	 * @return array|GenericEntity[]
	 */
	public function findAll()
	{
		$request = new GetJson('/admin/carrier_services.json');
		$response = $this->send($request);
		return $this->createCollection($response->get('carrier_services'));
	}

	/**
	 * @param int $carrierServiceId
	 * @return GenericEntity
	 */
	public function findOne($carrierServiceId)
	{
		$request = new GetJson('/admin/carrier_services/' . $carrierServiceId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('carrier_service'));
	}

	/**
	 * @param GenericEntity $carrierService
	 * @return GenericEntity
	 */
	public function create(GenericEntity $carrierService)
	{
		$request = new PostJson('/admin/carrier_services.json', array('carrier_service' => $carrierService->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('carrier_service'));
	}

	/**
	 * @param int $carrierServiceId
	 * @param GenericEntity $carrierService
	 * @return GenericEntity
	 */
	public function update($carrierServiceId, GenericEntity $carrierService)
	{
		$request = new PostJson('/admin/carrier_services/' . $carrierServiceId . '.json', array('carrier_service' => $carrierService->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('carrier_service'));
	}

	/**
	 * @param int $carrierServiceId
	 */
	public function delete($carrierServiceId)
	{
		$request = new DeleteParams('/admin/carrier_services/' . $carrierServiceId . '.json');
		$this->send($request);
	}
}