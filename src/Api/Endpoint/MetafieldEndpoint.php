<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class MetafieldEndpoint extends AbstractEndpoint
{
    /**
     * @param int $customerId
     * @param array $query
     * @return array|\CodeCloud\Bundle\ShopifyBundle\Api\GenericResource[]
     */
    public function findCustomerMetafields($customerId, array $query = array())
    {
        $request = new GetJson('/admin/customers/' . $customerId . '/metafields.json', $query);
        $response = $this->sendPaged($request, 'metafields');
        return $this->createCollection($response);
    }

    /**
     * @param array $query
     * @return array|\CodeCloud\Bundle\ShopifyBundle\Api\GenericResource[]
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
     * @return array|\CodeCloud\Bundle\ShopifyBundle\Api\GenericResource[]
     */
    public function findProductMetafields($productId, array $query = array())
    {
        $request = new GetJson('/admin/products/' . $productId . '/metafields.json', $query);
        $response = $this->sendPaged($request, 'metafields');
        return $this->createCollection($response);
    }

    /**
     * @param int $variantId
     * @param array $query
     * @return array|\CodeCloud\Bundle\ShopifyBundle\Api\GenericResource[]
     */
    public function findVariantMetafields($variantId, array $query = array())
    {
        $request = new GetJson('/admin/variants/' . $variantId . '/metafields.json', $query);
        $response = $this->sendPaged($request, 'metafields');
        return $this->createCollection($response);
    }

    /**
     * @param int $productImageId
     * @return array|\CodeCloud\Bundle\ShopifyBundle\Api\GenericResource[]
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
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function findOneCustomerMetafield($metafieldId, $customerId)
    {
        $request = new GetJson('/admin/customers/' . $customerId . '/metafields/' . $metafieldId . '.json');
        $response = $this->send($request);
        return $this->createEntity($response->get('metafield'));
    }

    /**
     * @param int $metafieldId
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
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
     * @return GenericResource
     */
    public function findOneProductMetafield($metafieldId, $productId)
    {
        $request = new GetJson('/admin/products/' . $productId . '/metafields/' . $metafieldId . '.json');
        $response = $this->send($request);
        return $this->createEntity($response->get('metafield'));
    }

    /**
     * @param int $customerId
     * @return int
     */
    public function countByCustomer($customerId)
    {
        $request = new GetJson('/admin/customers/' . $customerId . '/metafields/count.json');
        $response = $this->send($request);
        return $response->get('count');
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
     * @param int $customerId
     * @param GenericResource $metafield
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function createCustomerMetafield($customerId, GenericResource $metafield)
    {
        $request = new PostJson('/admin/customers/' . $customerId . '/metafields.json', array('metafield' => $metafield->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('metafield'));
    }

    /**
     * @param GenericResource $metafield
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function createStoreMetafield(GenericResource $metafield)
    {
        $request = new PostJson('/admin/metafields.json', array('metafield' => $metafield->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('metafield'));
    }

    /**
     * @param int $productId
     * @param GenericResource $metafield
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function createProductMetafield($productId, GenericResource $metafield)
    {
        $request = new PostJson('/admin/products/' . $productId . '/metafields.json', array('metafield' => $metafield->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('metafield'));
    }

    /**
     * @param int $metafieldId
     * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $metafield
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function updateStoreMetafield($metafieldId, GenericResource $metafield)
    {
        $request = new PutJson('/admin/metafields/' . $metafieldId . '.json', array('metafield' => $metafield->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('metafield'));
    }

    /**
     * @param int $metafieldId
     * @param int $customerId
     * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $metafield
     * @return GenericResource
     */
    public function updateCustomerMetafield($metafieldId, $customerId, GenericResource $metafield)
    {
        $request = new PutJson('/admin/products/' . $customerId . '/metafields/' . $metafieldId . '.json', array('metafield' => $metafield->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('metafield'));
    }

    /**
     * @param int $metafieldId
     * @param int $productId
     * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $metafield
     * @return GenericResource
     */
    public function updateProductMetafield($metafieldId, $productId, GenericResource $metafield)
    {
        $request = new PutJson('/admin/products/' . $productId . '/metafields/' . $metafieldId . '.json', array('metafield' => $metafield->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('metafield'));
    }

    /**
     * @param int $metafieldId
     * @param int $customerId
     */
    public function deleteCustomerMetafield($metafieldId, $customerId)
    {
        $request = new DeleteParams('/admin/customers/' . $customerId . '/metafields/' . $metafieldId . '.json');
        $this->send($request);
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
     * @param int $metafieldId
     * @param int $productId
     */
    public function deleteProductMetafield($metafieldId, $productId)
    {
        $request = new DeleteParams('/admin/products/' . $productId . '/metafields/' . $metafieldId . '.json');
        $this->send($request);
    }
}
