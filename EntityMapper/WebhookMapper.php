<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class WebhookMapper extends EntityMapper
{
	/**
	 * @param array $query
	 * @return array|GenericEntity[]
	 */
	public function findAll(array $query = array())
	{
		$request = new GetJson('/admin/webhooks.json', $query);
		$response = $this->sendPaged($request, 'webhooks');
		return $this->createCollection($response);
	}

	/**
	 * @param array $query
	 * @return int
	 */
	public function countAll(array $query = array())
	{
		$request = new GetJson('/admin/webhooks/count.json', $query);
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $webhookId
	 * @param array $fields
	 * @return GenericEntity
	 */
	public function findOne($webhookId, array $fields = array())
	{
		$params = $fields ? array('fields' => implode(',', $fields)) : array();
		$request = new GetJson('/admin/webhooks/' . $webhookId . '.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('webhook'));
	}

	/**
	 * @param GenericEntity $webhook
	 * @return GenericEntity
	 */
	public function create(GenericEntity $webhook)
	{
		$request = new PostJson('/admin/webooks.json', array('webhook' => $webhook->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('webhook'));
	}

	/**
	 * @param GenericEntity $webhook
	 * @return GenericEntity
	 */
	public function update($webhookId, GenericEntity $webhook)
	{
		$request = new PutJson('/admin/webooks/' . $webhookId . '.json', array('webhook' => $webhook->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('webhook'));
	}

	/**
	 * @param int $webhookId
	 */
	public function delete($webhookId)
	{
		$request = new DeleteParams('/admin/webooks/' . $webhookId . '.json');
		$this->send($request);
	}
}
