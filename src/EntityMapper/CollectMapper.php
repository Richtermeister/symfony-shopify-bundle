<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class CollectMapper extends EntityMapper
{
	/**
	 * @param array $query
	 * @return array|GenericEntity[]
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
	 * @return GenericEntity
	 */
	public function findOne($collectId)
	{
		$request = new GetJson('/admin/collects/' . $collectId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('collect'));
	}

	/**
	 * @param GenericEntity $collect
	 * @return GenericEntity
	 */
	public function create(GenericEntity $collect)
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