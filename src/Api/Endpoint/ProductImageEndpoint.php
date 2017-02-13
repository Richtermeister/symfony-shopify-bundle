<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class ProductImageEndpoint extends AbstractEndpoint
{
	/**
	 * @param int $productId
	 * @param array $query
	 * @return array|\CodeCloud\Bundle\ShopifyBundle\Api\GenericResource[]
	 */
	public function findByProduct($productId, array $query = array())
	{
		$request = new GetJson('/admin/products/' . $productId . '/images.json', $query);
		$response = $this->send($request);
		return $this->createCollection($response->get('images'));
	}

	/**
	 * @param int $productId
	 * @param array $query
	 * @return int
	 */
	public function countByProduct($productId, array $query = array())
	{
		$request = new GetJson('/admin/products/' . $productId . '/images/count.json', $query);
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $productId
	 * @param int $productImageId
	 * @return GenericResource
	 */
	public function findOne($productId, $productImageId, array $fields = array())
	{
		$params = $fields ? array('fields' => implode(',', $fields)) : array();
		$request = new GetJson('/admin/products/' . $productId . '/images/' . $productImageId . '.json', $params);
		$response = $this->send($request);
		return $this->createEntity($response->get('image'));
	}

	/**
	 * @param int $productId
	 * @param GenericResource $productImage
	 * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
	 */
	public function create($productId, GenericResource $productImage)
	{
		$request = new PostJson('/admin/products/' . $productId . '/images.json', array('image' => $productImage->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('image'));
	}

	/**
	 * @param int $productId
	 * @param int $productImageId
	 * @param GenericResource $productImage
	 * @return GenericResource
	 */
	public function update($productId, $productImageId, GenericResource $productImage)
	{
		$request = new PutJson('/admin/products/' . $productId . '/images/' . $productImageId . '.json', array('image' => $productImage->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('image'));
	}

	/**
	 * @param int $productId
	 * @param int $productImageId
	 */
	public function delete($productId, $productImageId)
	{
		$request = new DeleteParams('/admin/products/' . $productId . '/images/' . $productImageId . '.json');
		$this->send($request);
	}
}