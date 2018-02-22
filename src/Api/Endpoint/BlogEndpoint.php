<?php

namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class BlogEndpoint extends AbstractEndpoint
{
    /**
     * @param array $query
     * @return array|GenericResource[]
     */
    public function findAll(array $query = array())
    {
        $request = new GetJson('/admin/blogs.json', $query);
        $response = $this->send($request);
        return $this->createCollection($response->get('blogs'));
    }

    /**
     * @return int
     */
    public function countAll()
    {
        $request = new GetJson('/admin/blogs/count.json');
        $response = $this->send($request);
        return $response->get('count');
    }

    /**
     * @param int $blogId
     * @param array $fields
     * @return GenericResource
     */
    public function findOne($blogId, array $fields = array())
    {
        $params = $fields ? array('fields' => $fields) : array();
        $request = new GetJson('/admin/blogs/' . $blogId . '.json', $params);
        $response = $this->send($request);
        return $this->createEntity($response->get('blog'));
    }

    /**
     * @param GenericResource $blog
     * @return GenericResource
     */
    public function create(GenericResource $blog)
    {
        $request = new PostJson('/admin/blogs.json', array('blog' => $blog->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('blog'));
    }

    /**
     * @param int $blogId
     * @param GenericResource $blog
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function update($blogId, GenericResource $blog)
    {
        $request = new PostJson('/admin/blogs/' . $blogId . '.json', array('blog' => $blog->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('blog'));
    }

    /**
     * @param int $blogId
     */
    public function delete($blogId)
    {
        $request = new DeleteParams('/admin/blogs/' . $blogId . '.json');
        $this->send($request);
    }
}
