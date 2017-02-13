<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class FulFillmentEndpoint extends AbstractEndpoint
{
	/**
	 * @param int $orderId
	 * @param array $query
	 * @return array|\CodeCloud\Bundle\ShopifyBundle\Api\GenericResource[]
	 */
	public function findByOrder($orderId, array $query = array())
	{
		$request = new GetJson('/admin/orders/' . $orderId . '/fulfillments.json', $query);
		$response = $this->sendPaged($request, 'fulfillments');
		return $this->createCollection($response);
	}

	/**
	 * @param int $orderId
	 * @param array $query
	 * @return int
	 */
	public function countByOrder($orderId, array $query = array())
	{
		$request = new GetJson('/admin/orders/' . $orderId . '/fulfillments/count.json', $query);
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $orderId
	 * @param int $fulfillmentId
	 * @param array $fields
	 * @return GenericResource
	 */
	public function findOne($orderId, $fulfillmentId, array $fields = array())
	{
		$params = $fields ? array('fields' => $fields) : array();
		$request = new GetJson('/admin/orders/' . $orderId . '/fulfillments/' . $fulfillmentId . '.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('fulfillment'));
	}

	/**
	 * @param int $orderId
	 * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $fulfillment
	 * @return GenericResource
	 */
	public function create($orderId, GenericResource $fulfillment)
	{
		$request = new PostJson('/admin/orders/' . $orderId . '/fulfillments.json', array('fulfillment' => $fulfillment->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('fulfillment'));
	}

	/**
	 * @param int $orderId
	 * @param int $fulfillmentId
	 * @param GenericResource $fulfillment
	 * @return GenericResource
	 */
	public function update($orderId, $fulfillmentId, GenericResource $fulfillment)
	{
		$request = new PutJson('/admin/orders/' . $orderId . '/fulfillments/' . $fulfillmentId . '.json', array('fulfillment' => $fulfillment->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('fulfillment'));
	}

	/**
	 * @param int $orderId
	 * @param int $fulfillmentId
	 */
	public function complete($orderId, $fulfillmentId)
	{
		$request = new PostJson('/admin/orders/' . $orderId . '/fulfillments/' . $fulfillmentId . '/complete.json');
		$this->send($request);
	}

	/**
	 * @param int $orderId
	 * @param int $fulfillmentId
	 */
	public function cancel($orderId, $fulfillmentId)
	{
		$request = new PostJson('/admin/orders/' . $orderId . '/fulfillments/' . $fulfillmentId . '/cancel.json');
		$this->send($request);
	}
}