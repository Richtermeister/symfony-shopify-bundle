<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class WebhookEndpoint extends AbstractEndpoint
{
	/**
	 * @param array $query
	 * @return array|\CodeCloud\Bundle\ShopifyBundle\Api\GenericResource[]
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
	 * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
	 */
	public function findOne($webhookId, array $fields = array())
	{
		$params = $fields ? array('fields' => implode(',', $fields)) : array();
		$request = new GetJson('/admin/webhooks/' . $webhookId . '.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('webhook'));
	}

	/**
	 * @param GenericResource $webhook
	 * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
	 */
	public function create(GenericResource $webhook)
	{
		$request = new PostJson('/admin/webhooks.json', array('webhook' => $webhook->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('webhook'));
	}

	/**
	 * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $webhook
	 * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
	 */
	public function update($webhookId, GenericResource $webhook)
	{
		$request = new PutJson('/admin/webhooks/' . $webhookId . '.json', array('webhook' => $webhook->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('webhook'));
	}

	/**
	 * @param int $webhookId
	 */
	public function delete($webhookId)
	{
		$request = new DeleteParams('/admin/webhooks/' . $webhookId . '.json');
		$this->send($request);
	}
}
