<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class CustomerSavedSearchMapper extends EntityMapper
{
	/**
	 * @param array $query
	 * @return array|GenericEntity[]
	 */
	public function findAll(array $query = array())
	{
		$request = new GetJson('/admin/customer_saved_searches.json', $query);
		$response = $this->sendPaged($request, 'customer_saved_searches');
		return $this->createCollection($response);
	}

	/**
	 * @param array $query
	 * @return int
	 */
	public function countAll(array $query = array())
	{
		$request = new GetJson('/admin/customer_saved_searches/count.json', $query);
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $savedSearchId
	 * @param array $fields
	 * @return GenericEntity
	 */
	public function findOne($savedSearchId, array $fields = array())
	{
		$params = $fields ? array('fields' => $fields) : array();
		$request = new GetJson('/admin/customer_saved_searches/' . $savedSearchId . '.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('customer_saved_search'));
	}

	/**
	 * @param int $savedSearchId
	 * @return array|GenericEntity[]
	 */
	public function findCustomersForSavedSearch($savedSearchId, array $query = array())
	{
		$request = new GetJson('/admin/customer_saved_searches/' . $savedSearchId . '/customers.json', $query);
		$response = $this->sendPaged($request, 'customers');
		return $this->createCollection($response);
	}

	/**
	 * @param GenericEntity $savedSearch
	 * @return GenericEntity
	 */
	public function create(GenericEntity $savedSearch)
	{
		$request = new PostJson('/admin/customer_saved_searches.json', array('customer_saved_search' => $savedSearch->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('customer_saved_search'));
	}

	/**
	 * @param int $savedSearchId
	 * @param GenericEntity $savedSearch
	 * @return GenericEntity
	 */
	public function update($savedSearchId, GenericEntity $savedSearch)
	{
		$request = new PutJson('/admin/customer_saved_searches/' . $savedSearchId . '.json', array('customer_saved_search' => $savedSearch->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('customer_saved_search'));
	}

	/**
	 * @param int $savedSearchId
	 */
	public function delete($savedSearchId)
	{
		$request = new DeleteParams('/admin/customer_saved_searches/' . $savedSearchId . '.json');
		$this->send($request);
	}
}
