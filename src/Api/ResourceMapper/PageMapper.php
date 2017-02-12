<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class PageMapper extends AbstractResourceMapper
{
	/**
	 * @param array $query
	 * @return array|GenericResource
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
	 * @return GenericResource
	 */
	public function findOne($pageId, array $fields = array())
	{
		$params = $fields ? array('fields' => implode(',', $fields)) : array();
		$request = new GetJson('/admin/pages/' . $pageId . '.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('page'));
	}

	/**
	 * @param GenericResource $page
	 * @return GenericResource
	 */
	public function create(GenericResource $page)
	{
		$request = new PostJson('/admin/pages.json', array('page' => $page->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('page'));
	}

	/**
	 * @param int $pageId
	 * @param GenericResource $page
	 * @return GenericResource
	 */
	public function update($pageId, GenericResource $page)
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