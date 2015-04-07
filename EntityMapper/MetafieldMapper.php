<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class MetafieldMapper extends EntityMapper
{
	/**
	 * @param array $query
	 * @return array|GenericEntity[]
	 */
	public function findStoreMetafields(array $query = array())
	{
		$request = new GetJson('/admin/metafields.json', $query);
		$response = $this->sendPaged($request, 'metafields');
		return $this->createCollection($response);
	}

	/**
	 * @param int $productId
	 * @param array $query
	 * @return array|GenericEntity[]
	 */
	public function findProductMetafields($productId, array $query = array())
	{
		$request = new GetJson('/admin/products/' . $productId . '/metafields.json', $query);
		$response = $this->sendPaged($request, 'metafields');
		return $this->createCollection($response);
	}

	/**
	 * @param int $productImageId
	 * @return array|GenericEntity[]
	 */
	public function findProductImageMetafields($productImageId)
	{
		$params = array(
			'metafield[owner_id]'       => $productImageId,
			'metafield[owner_resource]' => 'product_image'
		);

		$request = new GetJson('/admin/metafields.json', $params);
		$response = $this->sendPaged($request, 'metafields');
		return $this->createCollection($response);
	}

	/**
	 * @param int $metafieldId
	 * @return GenericEntity
	 */
	public function findOneStoreMetafield($metafieldId)
	{
		$request = new GetJson('/admin/metafields/' . $metafieldId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('metafield'));
	}

	/**
	 * @param int $metafieldId
	 * @param int $productId
	 * @return GenericEntity
	 */
	public function findOneProductMetafield($metafieldId, $productId)
	{
		$request = new GetJson('/admin/products/' . $productId . '/metafields/' . $metafieldId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('metafield'));
	}

	/**
	 * @return int
	 */
	public function countStoreMetafields()
	{
		$request = new GetJson('/admin/metafields/count.json');
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param int $productId
	 * @return int
	 */
	public function countByProduct($productId)
	{
		$request = new GetJson('/admin/products/' . $productId . '/metafields/count.json');
		$response = $this->send($request);
		return $response->get('count');
	}

	/**
	 * @param GenericEntity $metafield
	 * @return GenericEntity
	 */
	public function createStoreMetafield(GenericEntity $metafield)
	{
		$request = new PostJson('/admin/metafields.json', array('metafield' => $metafield->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('metafield'));
	}

	/**
	 * @param int $metafieldId
	 * @param GenericEntity $metafield
	 * @return GenericEntity
	 */
	public function updateStoreMetafield($metafieldId, GenericEntity $metafield)
	{
		$request = new PutJson('/admin/metafields/' . $metafieldId . '.json', array('metafield' => $metafield->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('metafield'));
	}

	/**
	 * @param int $metafieldId
	 */
	public function deleteStoreMetafield($metafieldId)
	{
		$request = new DeleteParams('/admin/metafields/' . $metafieldId . '.json');
		$this->send($request);
	}

	/**
	 * @param int $productId
	 * @param GenericEntity $metafield
	 * @return GenericEntity
	 */
	public function createProductMetafield($productId, GenericEntity $metafield)
	{
		$request = new PostJson('/admin/products/' . $productId . '/metafields.json', array('metafield' => $metafield->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('metafield'));
	}

	/**
	 * @param int $metafieldId
	 * @param int $productId
	 * @param GenericEntity $metafield
	 * @return GenericEntity
	 */
	public function updateProductMetafield($metafieldId, $productId, GenericEntity $metafield)
	{
		$request = new PutJson('/admin/products/' . $productId . '/metafields/' . $metafieldId . '.json', array('metafield' => $metafield->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('metafield'));
	}

	/**
	 * @param int $metafieldId
	 * @param int $productId
	 */
	public function deleteProductMetafield($metafieldId, $productId)
	{
		$request = new DeleteParams('/admin/products/' . $productId . '/metafields/' . $metafieldId . '.json');
		$this->send($request);
	}
}