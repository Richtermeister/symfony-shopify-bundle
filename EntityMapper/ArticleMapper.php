<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class ArticleMapper extends EntityMapper
{
	/**
	 * @param int $blogId
	 * @param array $query
	 * @return array
	 */
	public function findByBlog($blogId, array $query = array())
	{
		$request = new GetJson('/admin/blogs/' . $blogId . '/articles.json', $query);
		$response = $this->sendPaged($request, 'articles');
		return $this->createCollection($response);
	}

	/**
	 * @param int $blogId
	 * @param array $query
	 * @return int
	 */
	public function countByBlog($blogId, array $query = array())
	{
		$request = new GetJson('/admin/blogs/' . $blogId . '/articles/count.json', $query);
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $blogId
	 * @param int $articleId
	 * @return GenericEntity
	 */
	public function findOne($blogId, $articleId)
	{
		$request = new GetJson('/admin/blogs/' . $blogId . '/articles/' . $articleId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('article'));
	}

	/**
	 * @param int $blogId
	 * @param GenericEntity $article
	 * @return GenericEntity
	 */
	public function create($blogId, GenericEntity $article)
	{
		$request = new PostJson('/admin/blogs/' . $blogId . '/articles.json', array('article' => $article->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('article'));
	}

	/**
	 * @param int $blogId
	 * @param int $articleId
	 * @param GenericEntity $article
	 * @return GenericEntity
	 */
	public function update($blogId, $articleId, GenericEntity $article)
	{
		$request = new PutJson('/admin/blogs/' . $blogId . '/articles/' . $articleId . '.json', array('article' => $article->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('article'));
	}

	/**
	 * @param int $blogId
	 * @param int $articleId
	 */
	public function delete($blogId, $articleId)
	{
		$request = new DeleteParams('/admin/blogs/' . $blogId . '/articles/' . $articleId . '.json');
		$this->send($request);
	}

	/**
	 * @return array
	 */
	public function findAllAuthors()
	{
		$request = new GetJson('/admin/articles/authors.json');
		$response = $this->send($request);
		return $response->get('authors');
	}

	/**
	 * @param array $query
	 * @return array
	 */
	public function findAllTags(array $query = array())
	{
		$request = new GetJson('/admin/articles/tags.json', $query);
		$response = $this->send($request);
		return $response->get('tags');
	}
}