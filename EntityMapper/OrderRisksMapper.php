<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class OrderRisksMapper extends EntityMapper
{
	/**
	 * @param int $orderId
	 * @return array|GenericEntity
	 */
	public function findByOrder($orderId)
	{
		$request = new GetJson('/admin/orders/' . $orderId . '/risks.json');
		$response = $this->send($request);
		return $this->createCollection($response->get('risks'));
	}

	/**
	 * @param int $orderId
	 * @param int $orderRisksId
	 * @return GenericEntity
	 */
	public function findOne($orderId, $orderRisksId)
	{
		$request = new GetJson('/admin/orders/' . $orderId . '/risks/' . $orderRisksId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('risk'));
	}

	/**
	 * @param int $orderId
	 * @param GenericEntity $orderRisks
	 * @return GenericEntity
	 */
	public function create($orderId, GenericEntity $orderRisks)
	{
		$request = new PostJson('/admin/orders/' . $orderId . '/risks.json', $orderRisks);
		$response = $this->send($request);
		return $this->createEntity($response->get('risk'));
	}

	/**
	 * @param int $orderId
	 * @param int $orderRisksId
	 * @param GenericEntity $orderRisks
	 * @return GenericEntity
	 */
	public function update($orderId, $orderRisksId, GenericEntity $orderRisks)
	{
		$request = new PutJson('/admin/orders/' . $orderId . '/risks/' . $orderRisksId . '.json', array('risk' => $orderRisks->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('risk'));
	}

	/**
	 * @param int $orderId
	 * @param int $orderRisksId
	 */
	public function delete($orderId, $orderRisksId)
	{
		$request = new DeleteParams('/admin/orders/' . $orderId . '/risks/' . $orderRisksId . '.json');
		$this->send($request);
	}
}