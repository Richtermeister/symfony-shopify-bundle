<?php

namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class ScriptTagEndpoint extends AbstractEndpoint
{
    /**
     * @param array $fields
     * @return array|GenericResource[]
     */
    public function findAll(array $fields = array())
    {
        $params = $fields ? array('fields' => implode(',', $fields)) : array();
        $request = new GetJson('/admin/script_tags.json', $params);
        $response = $this->send($request);

        return $this->createCollection($response->get('script_tags'));
    }

    /**
     * @param int $id
     * @return GenericResource
     */
    public function findOne($id)
    {
        $request = new GetJson('/admin/script_tags/' . $id . '.json');
        $response = $this->send($request);

        return $this->createEntity($response->get('script_tag'));
    }

    /**
     * @param GenericResource $resource
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function create(GenericResource $resource)
    {
        $request = new PostJson('/admin/script_tags.json', array('script_tag' => $resource->toArray()));
        $response = $this->send($request);

        return $this->createEntity($response->get('script_tag'));
    }

    /**
     * @param int $id
     * @param GenericResource $resource
     * @return GenericResource
     */
    public function update($id, $resource)
    {
        $request = new PutJson('/admin/script_tags/' . $id . '.json', array('script_tag' => $resource->toArray()));
        $response = $this->send($request);

        return $this->createEntity($response->get('script_tag'));
    }

    /**
     * @param int $id
     */
    public function delete($id)
    {
        $request = new DeleteParams('/admin/script_tags/' . $id . '.json');
        $this->send($request);
    }
}
