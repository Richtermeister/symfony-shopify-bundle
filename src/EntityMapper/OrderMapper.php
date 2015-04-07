<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class OrderMapper extends EntityMapper
{
	/**
	 * @param array $query
	 * @return array|GenericEntity[]
	 */
	public function findAll(array $query = array())
	{
		$request = new GetJson('/admin/orders.json', $query);
		$response = $this->sendPaged($request, 'orders');
		return $this->createCollection($response);
	}

	/**
	 * @param int $orderId
	 * @param array $fields
	 * @return GenericEntity
	 */
	public function findOne($orderId, array $fields = array())
	{
		$params = $fields ? array('fields' => implode(',', $fields)) : array();
		$request = new GetJson('/admin/orders/' . $orderId . '.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('order'));
	}

	/**
	 * @param array $query
	 * @return int
	 */
	public function countAll(array $query = array())
	{
		$request = new GetJson('/admin/orders/count.json', $query);
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param GenericEntity $order
	 * @return GenericEntity
	 */
	public function create(GenericEntity $order)
	{
		$request = new PostJson('/admin/orders.json', array('order' => $order->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('order'));
	}

	/**
	 * @param int $orderId
	 * @param GenericEntity $order
	 * @return GenericEntity
	 */
	public function update($orderId, GenericEntity $order)
	{
		$request = new PutJson('/admin/orders/' . $orderId . '.json', array('order' => $order->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('order'));
	}

	/**
	 * @param int $orderId
	 */
	public function delete($orderId)
	{
		$request = new DeleteParams('/admin/orders/' . $orderId . '.json');
		$this->send($request);
	}

	/**
	 * @param int $orderId
	 */
	public function close($orderId)
	{
		$request = new PostJson('/admin/orders/' . $orderId . '/close.json');
		$this->send($request);
	}

	/**
	 * @param int $orderId
	 */
	public function open($orderId)
	{
		$request = new PostJson('/admin/orders/' . $orderId . '/open.json');
		$this->send($request);
	}

	/**
	 * @param int $orderId
	 * @param array $options
	 */
	public function cancel($orderId, array $options = array())
	{
		$request = new PostJson('/admin/orders/' . $orderId . '/cancel.json', $options);
		$this->send($request);
	}
}