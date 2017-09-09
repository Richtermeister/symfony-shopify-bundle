<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class CustomCollectionEndpoint extends AbstractEndpoint
{
    /**
     * @param array $query
     * @return array|GenericResource[]
     */
    public function findAll(array $query = array())
    {
        $request = new GetJson('/admin/custom_collections.json', $query);
        $response = $this->sendPaged($request, 'custom_collections');
        return $this->createCollection($response);
    }

    /**
     * @param array $query
     * @return int
     */
    public function countAll(array $query = array())
    {
        $request = new GetJson('/admin/custom_collections/count.json', $query);
        $response = $this->send($request);
        return $response->get('count');
    }

    /**
     * @param int $customCollectionId
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function findOne($customCollectionId)
    {
        $request = new GetJson('/admin/custom_collections/' . $customCollectionId . '.json');
        $response = $this->send($request);
        return $this->createEntity($response->get('custom_collection'));
    }

    /**
     * @param GenericResource $customCollection
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function create(GenericResource $customCollection)
    {
        $request = new PostJson('/admin/custom_collections.json', array('custom_collection' => $customCollection->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('custom_collection'));
    }

    /**
     * @param int $customCollectionId
     * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $customCollection
     * @return GenericResource
     */
    public function update($customCollectionId, GenericResource $customCollection)
    {
        $request = new PutJson('/admin/custom_collections/' . $customCollectionId . '.json', array('custom_collection' => $customCollection->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('custom_collection'));
    }

    /**
     * @param int $customCollectionId
     */
    public function delete($customCollectionId)
    {
        $request = new DeleteParams('/admin/custom_collections/' . $customCollectionId . '.json');
        $this->send($request);
    }
}
