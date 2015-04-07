<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class CustomerMapper extends EntityMapper
{
	/**
	 * @param array $query
	 * @return array|GenericEntity[]
	 */
	public function findAll(array $query = array())
	{
		$request = new GetJson('/admin/customers.json', $query);
		$response = $this->sendPaged($request, 'customers');
		return $this->createCollection($response);
	}

	/**
	 * @param int $customerId
	 * @param array $fields
	 * @return array|GenericEntity[]
	 */
	public function findOrdersForCustomer($customerId, array $fields = array())
	{
		$params = $fields ? array('fields' => implode(',', $fields)) : array();
		$request = new GetJson('/admin/customers/' . $customerId . '.json', $params);
		$response = $this->sendPaged($request, 'customers');
		return $this->createCollection($response);
	}

	/**
	 * @param array $query
	 * @return array|GenericEntity[]
	 */
	public function search(array $query = array())
	{
		$request = new GetJson('/admin/customers/search.json', $query);
		$response = $this->sendPaged($request, 'customers');
		return $this->createCollection($response);
	}

	/**
	 * @return int
	 */
	public function countAll()
	{
		$request = new GetJson('/admin/customers/count.json');
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $customerId
	 * @return GenericEntity
	 */
	public function findOne($customerId)
	{
		$request = new GetJson('/admin/customers/' . $customerId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('customer'));
	}

	/**
	 * @param GenericEntity $customer
	 * @return GenericEntity
	 */
	public function create(GenericEntity $customer)
	{
		$request = new PostJson('/admin/customers.json', array('customer' => $customer->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('customer'));
	}

	/**
	 * @param int $customerId
	 * @param GenericEntity $customer
	 * @return GenericEntity
	 */
	public function update($customerId, GenericEntity $customer)
	{
		$request = new PostJson('/admin/customers/' . $customerId . '.json', array('customer' => $customer->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('customer'));
	}

	/**
	 * @param int $customerId
	 */
	public function delete($customerId)
	{
		$request = new DeleteParams('/admin/customers/' . $customerId . '.json');
		$this->send($request);
	}
}
