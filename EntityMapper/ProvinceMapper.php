<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class ProvinceMapper extends EntityMapper
{
	/**
	 * @param int $countryId
	 * @param array $query
	 * @return array|GenericEntity[]
	 */
	public function findByCountry($countryId, array $query = array())
	{
		$request = new GetJson('/admin/countries/' . $countryId . '/provinces.json', $query);
		$response = $this->send($request);
		return $this->createCollection($response->get('provinces'));
	}

	/**
	 * @param int $countryId
	 * @param array $query
	 * @return int
	 */
	public function countByCountry($countryId, array $query = array())
	{
		$request = new GetJson('/admin/countries/' . $countryId . '/provinces.json', $query);
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $countryId
	 * @param int $provinceId
	 * @return GenericEntity
	 */
	public function findOne($countryId, $provinceId)
	{
		$request = new GetJson('/admin/countries/' . $countryId . '/provinces/' . $provinceId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('province'));
	}

	/**
	 * @param int $countryId
	 * @param int $provinceId
	 * @param GenericEntity $province
	 * @return GenericEntity
	 */
	public function update($countryId, $provinceId, GenericEntity $province)
	{
		$request = new PutJson('/admin/countries/' . $countryId . '/provinces/' . $provinceId . '.json', array('province' => $province->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('province'));
	}
}
