<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class PageMapper extends EntityMapper
{
	/**
	 * @param array $query
	 * @return array|GenericEntity
	 */
	public function findAll(array $query = array())
	{
		$request = new GetJson('/admin/pages.json', $query);
		$response = $this->sendPaged($request, 'pages');
		return $this->createCollection($response->get('pages'));
	}

	/**
	 * @param array $query
	 * @return array
	 */
	public function countAll(array $query = array())
	{
		$request = new GetJson('/admin/pages.json', $query);
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $pageId
	 * @param array $fields
	 * @return GenericEntity
	 */
	public function findOne($pageId, array $fields = array())
	{
		$params = $fields ? array('fields' => implode(',', $fields)) : array();
		$request = new GetJson('/admin/pages/' . $pageId . '.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('page'));
	}

	/**
	 * @param GenericEntity $page
	 * @return GenericEntity
	 */
	public function create(GenericEntity $page)
	{
		$request = new PostJson('/admin/pages.json', array('page' => $page->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('page'));
	}

	/**
	 * @param int $pageId
	 * @param GenericEntity $page
	 * @return GenericEntity
	 */
	public function update($pageId, GenericEntity $page)
	{
		$request = new PutJson('/admin/pages/' . $pageId. '.json', array('page' => $page->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('page'));
	}

	/**
	 * @param int $pageId
	 */
	public function delete($pageId)
	{
		$request = new DeleteParams('/admin/pages/' . $pageId . '.json');
		$this->send($request);
	}
}