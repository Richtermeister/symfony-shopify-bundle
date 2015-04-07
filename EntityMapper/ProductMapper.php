<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class ProductMapper extends EntityMapper
{
	/**
	 * @param array $query
	 * @return array|GenericEntity[]
	 */
	public function findAll(array $query = array())
	{
		$request = new GetJson('/admin/products.json', $query);
		$response = $this->sendPaged($request, 'products');
		return $this->createCollection($response);
	}

	/**
	 * @param array $query
	 * @return int
	 */
	public function countAll(array $query = array())
	{
		$request = new GetJson('/admin/products/count.json', $query);
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $productId
	 * @return GenericEntity
	 */
	public function findOne($productId)
	{
		$request = new GetJson('/admin/products/' . $productId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('product'));
	}

	/**
	 * @param GenericEntity $product
	 * @return GenericEntity
	 */
	public function create(GenericEntity $product)
	{
		$request = new PostJson('/admin/products.json', array('product' => $product->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('product'));
	}

	/**
	 * @param int $productId
	 * @param GenericEntity $product
	 * @return GenericEntity
	 */
	public function update($productId, GenericEntity $product)
	{
		$request = new PutJson('/admin/products/' . $productId . '.json', array('product' => $product->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('product'));
	}

	/**
	 * @param int $productId
	 */
	public function delete($productId)
	{
		$request = new DeleteParams('/admin/products/' . $productId . '.json');
		$this->send($request);
	}
}