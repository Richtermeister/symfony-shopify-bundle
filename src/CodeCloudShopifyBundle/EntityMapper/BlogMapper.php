<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class BlogMapper extends EntityMapper
{
	/**
	 * @param array $query
	 * @return array|GenericEntity[]
	 */
	public function findAll(array $query = array())
	{
		$request = new GetJson('/admin/blogs.json', $query);
		$response = $this->send($request);
		return $this->createCollection($response->get('blogs'));
	}

	/**
	 * @return int
	 */
	public function countAll()
	{
		$request = new GetJson('/admin/blogs/count.json');
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $blogId
	 * @param array $fields
	 * @return GenericEntity
	 */
	public function findOne($blogId, array $fields = array())
	{
		$params = $fields ? array('fields' => $fields) : array();
		$request = new GetJson('/admin/blogs/' . $blogId . '.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('blog'));
	}

	/**
	 * @param GenericEntity $blog
	 * @return GenericEntity
	 */
	public function create(GenericEntity $blog)
	{
		$request = new PostJson('/admin/blogs.json', array('blog' => $blog->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('blog'));
	}

	/**
	 * @param int $blogId
	 * @param GenericEntity $blog
	 * @return GenericEntity
	 */
	public function update($blogId, GenericEntity $blog)
	{
		$request = new PostJson('/admin/blogs/' . $blogId . '.json', array('blog' => $blog->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('blog'));
	}

	/**
	 * @param int $blogId
	 */
	public function delete($blogId)
	{
		$request = new DeleteParams('/admin/blogs/' . $blogId . '.json');
		$this->send($request);
	}
}