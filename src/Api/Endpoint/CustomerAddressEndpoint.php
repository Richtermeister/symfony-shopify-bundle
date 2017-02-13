<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class CustomerAddressEndpoint extends AbstractEndpoint
{
	/**
	 * @param int $customerId
	 * @return array
	 */
	public function findByCustomer($customerId)
	{
		$request = new GetJson('/admin/customers/' . $customerId . '.json');
		$response = $this->sendPaged($request, 'addresses');
		return $this->createCollection($response);
	}

	/**
	 * @param int $customerId
	 * @param int $addressId
	 * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
	 */
	public function findOne($customerId, $addressId)
	{
		$request = new GetJson('/admin/customers/' . $customerId . '/addresses/' . $addressId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('customer_address'));
	}

	/**
	 * @param int $customerId
	 * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $address
	 * @return GenericResource
	 */
	public function create($customerId, GenericResource $address)
	{
		$request = new PostJson('/admin/customers/' . $customerId . '/addresses.json', array('address' => $address->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('customer_address'));
	}

	/**
	 * @param int $customerId
	 * @param int $addressId
	 * @param GenericResource $address
	 * @return GenericResource
	 */
	public function update($customerId, $addressId, GenericResource $address)
	{
		$request = new PutJson('/admin/customers/' . $customerId . '/addresses/' . $addressId . '.json', array('address' => $address->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('customer_address'));
	}

	/**
	 * @param int $customerId
	 * @param int $addressId
	 */
	public function delete($customerId, $addressId)
	{
		$request = new DeleteParams('/admin/customers/' . $customerId . '/addresses/' . $addressId . '.json');
		$this->send($request);
	}

	/**
	 * @param int $customerId
	 * @param array $addressIds
	 * @param array $bulkOperation
	 */
	public function bulkOperation($customerId, array $addressIds, $bulkOperation)
	{
		$queryString = array();

		foreach ($addressIds as $addressId) {
			$queryString[] = 'address_ids[]=' . $addressId;
		}

		$queryString[] = 'operation=' . $bulkOperation;

		$request = new PutJson('/admin/customers/' . $customerId . '/addresses/set.json?' . implode('&', $queryString));
		$this->send($request);
	}

	/**
	 * @param int $customerId
	 * @param int $addressId
	 */
	public function setAsDefault($customerId, $addressId)
	{
		$request = new PutJson('/admin/customers/' . $customerId . '/addresses/' . $addressId . '/default.json');
		$this->send($request);
	}
}