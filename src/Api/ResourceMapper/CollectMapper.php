<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class CollectMapper extends AbstractResourceMapper
{
	/**
	 * @param array $query
	 * @return array|GenericResource[]
	 */
	public function findAll(array $query = array())
	{
		$request = new GetJson('/admin/collects.json', $query);
		$response = $this->sendPaged($request, 'collects');
		return $this->createCollection($response);
	}

	/**
	 * @param array $query
	 * @return int
	 */
	public function countAll(array $query = array())
	{
		$request = new GetJson('/admin/collects/count.json', $query);
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $collectId
	 * @return GenericResource
	 */
	public function findOne($collectId)
	{
		$request = new GetJson('/admin/collects/' . $collectId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('collect'));
	}

	/**
	 * @param GenericResource $collect
	 * @return GenericResource
	 */
	public function create(GenericResource $collect)
	{
		$request = new PostJson('/admin/collects.json', array('collect' => $collect->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('collect'));
	}

	/**
	 * @param int $collectId
	 */
	public function delete($collectId)
	{
		$request = new DeleteParams('/admin/collects/' . $collectId . '.json');
		$this->send($request);
	}
}