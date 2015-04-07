<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class ProductVariantMapper extends EntityMapper
{
	/**
	 * @param int $productId
	 * @param array $query
	 * @return array|GenericEntity[]
	 */
	public function findByProduct($productId, array $query = array())
	{
		$request = new GetJson('/admin/products/' . $productId . '/variants.json', $query);
		$response = $this->sendPaged($request, 'variants');
		return $this->createCollection($response);
	}

	/**
	 * @param int $productId
	 * @return int
	 */
	public function countByProduct($productId)
	{
		$request = new GetJson('/admin/products/' . $productId . '/variants/count.json');
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $variantId
	 * @return GenericEntity
	 */
	public function findOne($variantId)
	{
		$request = new GetJson('/admin/variants/' . $variantId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('variant'));
	}

	/**
	 * @param int $productId
	 * @param GenericEntity $variant
	 * @return GenericEntity
	 */
	public function create($productId, GenericEntity $variant)
	{
		$request = new PostJson('/admin/products/' . $productId . '/variants.json', array('variant' => $variant->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('variant'));
	}

	/**
	 * @param int $variantId
	 * @param GenericEntity $variant
	 * @return GenericEntity
	 */
	public function update($variantId, GenericEntity $variant)
	{
		$request = new PutJson('/admin/variants/' . $variantId . '.json', array('variant' => $variant->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('variant'));
	}

	/**
	 * @param int $variantId
	 */
	public function delete($variantId)
	{
		$request = new DeleteParams('/admin/variants/' . $variantId . '.json');
		$this->send($request);
	}
}
