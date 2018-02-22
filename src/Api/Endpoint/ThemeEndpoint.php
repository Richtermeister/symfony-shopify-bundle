<?php

namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class ThemeEndpoint extends AbstractEndpoint
{
    /**
     * @param array $fields
     * @return array|GenericResource[]
     */
    public function findAll(array $fields = array())
    {
        $params = $fields ? array('fields' => implode(',', $fields)) : array();
        $request = new GetJson('/admin/themes.json', $params);
        $response = $this->send($request);
        return $this->createCollection($response->get('themes'));
    }

    /**
     * @param int $themeId
     * @param array $fields
     * @return GenericResource
     */
    public function findOne($themeId, array $fields = array())
    {
        $params = $fields ? array('fields' => implode(',', $fields)) : array();
        $request = new GetJson('/admin/themes/' . $themeId . '.json', $params);
        $response = $this->send($request);
        return $this->createEntity($response->get('theme'));
    }

    /**
     * @param GenericResource $theme
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function create(GenericResource $theme)
    {
        $request = new PostJson('/admin/themes.json', array('theme' => $theme->toArray()));
        $response = $this->send($request);
        return $this->create($response->get('theme'));
    }

    /**
     * @param int $themeId
     * @param GenericResource $theme
     * @return GenericResource
     */
    public function update($themeId, $theme)
    {
        $request = new PutJson('/admin/themes/' . $themeId . '.json', array('theme' => $theme->toArray()));
        $response = $this->send($request);
        return $this->create($response->get('theme'));
    }

    /**
     * @param int $themeId
     */
    public function delete($themeId)
    {
        $request = new DeleteParams('/admin/themes/' . $themeId . '.json');
        $this->send($request);
    }
}
