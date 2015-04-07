<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class ProductImageMapper extends EntityMapper
{
	/**
	 * @param int $productId
	 * @param array $query
	 * @return array|GenericEntity[]
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
	 * @return GenericEntity
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
	 * @param GenericEntity $productImage
	 * @return GenericEntity
	 */
	public function create($productId, GenericEntity $productImage)
	{
		$request = new PostJson('/admin/products/' . $productId . '/images.json', array('image' => $productImage->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('image'));
	}

	/**
	 * @param int $productId
	 * @param int $productImageId
	 * @param GenericEntity $productImage
	 * @return GenericEntity
	 */
	public function update($productId, $productImageId, GenericEntity $productImage)
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