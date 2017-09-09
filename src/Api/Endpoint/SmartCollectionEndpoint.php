<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class SmartCollectionEndpoint extends AbstractEndpoint
{
    /**
     * @param array $query
     * @return array|\CodeCloud\Bundle\ShopifyBundle\Api\GenericResource[]
     */
    public function findAll(array $query = array())
    {
        $request = new GetJson('/admin/smart_collections.json', $query);
        $response = $this->sendPaged($request, 'smart_collections');
        return $this->createCollection($response);
    }

    /**
     * @param array $query
     * @return int
     */
    public function countAll(array $query = array())
    {
        $request = new GetJson('/admin/smart_collections/count.json', $query);
        $response = $this->send($request);
        return $response->get('count');
    }

    /**
     * @param int $smartCollectionId
     * @param array $fields
     * @return GenericResource
     */
    public function findOne($smartCollectionId, array $fields = array())
    {
        $params = $fields ? array('params' => implode(',', $fields)) : array();
        $request = new GetJson('/admin/smart_collections/' . $smartCollectionId . '.json', $params);
        $response = $this->send($request);
        return $this->createEntity($response->get('smart_collection'));
    }

    /**
     * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $smartCollection
     * @return GenericResource
     */
    public function create(GenericResource $smartCollection)
    {
        $request = new PostJson('/admin/smart_collections.json', array('smart_collection' => $smartCollection->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('smart_collection'));
    }

    /**
     * @param int $smartCollectionId
     * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $smartCollection
     * @return GenericResource
     */
    public function update($smartCollectionId, GenericResource $smartCollection)
    {
        $request = new PutJson('/admin/smart_collections/' . $smartCollectionId . '.json', array('smart_collection' => $smartCollection->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('smart_collection'));
    }

    /**
     * @param int $smartCollectionId
     */
    public function delete($smartCollectionId)
    {
        $request = new DeleteParams('/admin/smart_collections/' . $smartCollectionId . '.json');
        $this->send($request);
    }

    /**
     * @param int $smartCollectionId
     * @param string $sort_order
     * @param array $productIds
     */
    public function setOrder($smartCollectionId, $sort_order = null, array $productIds = array())
    {
        $params = array();

        if ($sort_order) {
            $params[] = 'sort_order=' . $sort_order;
        }

        foreach ($productIds as $productId) {
            $params[] = 'products[]=' . $productId;
        }

        $url = '/admin/smart_collections/' . $smartCollectionId . '/order.json' . ($params ? '?' . implode('&', $params) : '');

        $request = new PutJson($url);
        $this->send($request);
    }
}
