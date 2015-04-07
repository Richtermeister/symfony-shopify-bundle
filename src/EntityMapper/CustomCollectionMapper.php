<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class CustomCollectionMapper extends EntityMapper
{
	/**
	 * @param array $query
	 * @return array|GenericEntity[]
	 */
	public function findAll(array $query = array())
	{
		$request = new GetJson('/admin/custom_collections.json', $query);
		$response = $this->sendPaged($request, 'custom_collections');
		return $this->createCollection($response);
	}

	/**
	 * @param array $query
	 * @return int
	 */
	public function countAll(array $query = array())
	{
		$request = new GetJson('/admin/custom_collections/count.json', $query);
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $customCollectionId
	 * @return GenericEntity
	 */
	public function findOne($customCollectionId)
	{
		$request = new GetJson('/admin/custom_collections/' . $customCollectionId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('custom_collection'));
	}

	/**
	 * @param GenericEntity $customCollection
	 * @return GenericEntity
	 */
	public function create(GenericEntity $customCollection)
	{
		$request = new PostJson('/admin/custom_collections.json', array('custom_collection' => $customCollection->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('custom_collection'));
	}

	/**
	 * @param int $customCollectionId
	 * @param GenericEntity $customCollection
	 * @return GenericEntity
	 */
	public function update($customCollectionId, GenericEntity $customCollection)
	{
		$request = new PutJson('/admin/custom_collections/' . $customCollectionId . '.json', array('custom_collection' => $customCollection->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('custom_collection'));
	}

	/**
	 * @param int $customCollectionId
	 */
	public function delete($customCollectionId)
	{
		$request = new DeleteParams('/admin/custom_collections/' . $customCollectionId . '.json');
		$this->send($request);
	}
}