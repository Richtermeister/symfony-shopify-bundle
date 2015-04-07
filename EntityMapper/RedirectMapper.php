<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class RedirectMapper extends EntityMapper
{
	/**
	 * @param array $query
	 * @return array|GenericEntity[]
	 */
	public function findAll(array $query = array())
	{
		$request = new GetJson('/admin/redirects.json', $query);
		$response = $this->sendPaged($request, 'redirects');
		return $this->createCollection($response);
	}

	/**
	 * @param array $query
	 * @return int
	 */
	public function countAll(array $query = array())
	{
		$request = new GetJson('/admin/redirects/count.json', $query);
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $redirectId
	 * @param array $fields
	 * @return GenericEntity
	 */
	public function findOne($redirectId, array $fields = array())
	{
		$params = $fields ? array('fields' => implode(',', $fields)) : array();
		$request = new GetJson('/admin/redirects/' . $redirectId . '.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('redirect'));
	}

	/**
	 * @param GenericEntity $redirect
	 * @return GenericEntity
	 */
	public function create(GenericEntity $redirect)
	{
		$request = new PostJson('/admin/redirects.json', array('redirect' => $redirect->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('redirect'));
	}

	/**
	 * @param int $redirectId
	 * @param GenericEntity $redirect
	 * @return GenericEntity
	 */
	public function update($redirectId, GenericEntity $redirect)
	{
		$request = new PutJson('/admin/redirects/' . $redirectId . '.json', array('redirect' => $redirect->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('redirect'));
	}

	/**
	 * @param int $redirectId
	 */
	public function delete($redirectId)
	{
		$request = new DeleteParams('/admin/redirects/' . $redirectId . '.json');
		$this->send($request);
	}
}