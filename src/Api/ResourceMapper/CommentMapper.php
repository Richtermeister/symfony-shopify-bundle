<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class CommentMapper extends ResourceMapper
{
	/**
	 * @param array $query
	 * @return array|GenericResource[]
	 */
	public function findAll(array $query = array())
	{
		$request = new GetJson('/admin/comments.json', $query);
		$response = $this->sendPaged($request, 'comments');
		return $this->createCollection($response);
	}

	/**
	 * @param array $query
	 * @return int
	 */
	public function countAll(array $query = array())
	{
		$request = new GetJson('/admin/comments/count.json', $query);
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $commentId
	 * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
	 */
	public function findOne($commentId)
	{
		$request = new GetJson('/admin/comments/' . $commentId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('comment'));
	}

	/**
	 * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $comment
	 * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
	 */
	public function create(GenericResource $comment)
	{
		$request = new PostJson('/admin/comments.json', array('comment' => $comment->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('comment'));
	}

	/**
	 * @param int $commentId
	 * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $comment
	 * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
	 */
	public function update($commentId, GenericResource $comment)
	{
		$request = new PutJson('/admin/comments/' . $commentId . '.json', array('comment' => $comment->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('comment'));
	}

	/**
	 * @param int $commentId
	 */
	public function markAsSpam($commentId)
	{
		$request = new PostJson('/admin/comments/' . $commentId . '/spam.json');
		$this->send($request);
	}

	/**
	 * @param int $commentId
	 */
	public function markAsNotSpam($commentId)
	{
		$request = new PostJson('/admin/comments/' . $commentId . '/not_spam.json');
		$this->send($request);
	}

	/**
	 * @param int $commentId
	 */
	public function approve($commentId)
	{
		$request = new PostJson('/admin/comments/' . $commentId . '/approve.json');
		$this->send($request);
	}

	/**
	 * @param int $commentId
	 */
	public function remove($commentId)
	{
		$request = new PostJson('/admin/comments/' . $commentId . '/remove.json');
		$this->send($request);
	}

	/**
	 * @param int $commentId
	 */
	public function restore($commentId)
	{
		$request = new PostJson('/admin/comments/' . $commentId . '/restore.json');
		$this->send($request);
	}
}