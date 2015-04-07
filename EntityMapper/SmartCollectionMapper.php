<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class SmartCollectionMapper extends EntityMapper
{
	/**
	 * @param array $query
	 * @return array|GenericEntity[]
	 */
	public function findAll(array $query = array())
	{
		$request = new GetJson('/admin/smart_collections.json', $query);
		$response = $this->sendPaged($request, 'smart_collections');
		return $this->createCollection($response);
	}

	/**
	 * @param array $query
	 * @return int
	 */
	public function countAll(array $query = array())
	{
		$request = new GetJson('/admin/smart_collections/count.json', $query);
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $smartCollectionId
	 * @param array $fields
	 * @return GenericEntity
	 */
	public function findOne($smartCollectionId, array $fields = array())
	{
		$params = $fields ? array('params' => implode(',', $fields)) : array();
		$request = new GetJson('/admin/smart_collections/' . $smartCollectionId . '.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('smart_collection'));
	}

	/**
	 * @param GenericEntity $smartCollection
	 * @return GenericEntity
	 */
	public function create(GenericEntity $smartCollection)
	{
		$request = new PostJson('/admin/smart_collections.json', array('smart_collection' => $smartCollection->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('smart_collection'));
	}

	/**
	 * @param int $smartCollectionId
	 * @param GenericEntity $smartCollection
	 * @return GenericEntity
	 */
	public function update($smartCollectionId, GenericEntity $smartCollection)
	{
		$request = new PutJson('/admin/smart_collections/' . $smartCollectionId . '.json', array('smart_collection' => $smartCollection->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('smart_collection'));
	}

	/**
	 * @param int $smartCollectionId
	 */
	public function delete($smartCollectionId)
	{
		$request = new DeleteParams('/admin/smart_collections/' . $smartCollectionId . '.json');
		$this->send($request);
	}

	/**
	 * @param int $smartCollectionId
	 * @param string $sort_order
	 * @param array $productIds
	 */
	public function setOrder($smartCollectionId, $sort_order = null, array $productIds = array())
	{
		$params = array();

		if ($sort_order) {
			$params[] = 'sort_order=' . $sort_order;
		}

		foreach ($productIds as $productId) {
			$params[] = 'products[]=' . $productId;
		}

		$url = '/admin/smart_collections/' . $smartCollectionId . '/order.json' . ($params ? '?' . implode('&', $params) : '');

		$request = new PutJson($url);
		$this->send($request);
	}
}