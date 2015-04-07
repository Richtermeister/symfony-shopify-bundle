<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;

class RefundMapper extends EntityMapper
{
	/**
	 * @param int $orderId
	 * @param int $refundId
	 * @param array $fields
	 * @return GenericEntity
	 */
	public function findOne($orderId, $refundId, array $fields = array())
	{
		$params = $fields ? array('fields' => implode(',', $fields)) : array();
		$request = new GetJson('/admin/orders/' . $orderId . '/refunds/' . $refundId . '.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('refund'));
	}
}