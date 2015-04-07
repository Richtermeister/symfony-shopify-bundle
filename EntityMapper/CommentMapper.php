<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class CommentMapper extends EntityMapper
{
	/**
	 * @param array $query
	 * @return array|GenericEntity[]
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
	 * @return GenericEntity
	 */
	public function findOne($commentId)
	{
		$request = new GetJson('/admin/comments/' . $commentId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('comment'));
	}

	/**
	 * @param GenericEntity $comment
	 * @return GenericEntity
	 */
	public function create(GenericEntity $comment)
	{
		$request = new PostJson('/admin/comments.json', array('comment' => $comment->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('comment'));
	}

	/**
	 * @param int $commentId
	 * @param GenericEntity $comment
	 * @return GenericEntity
	 */
	public function update($commentId, GenericEntity $comment)
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