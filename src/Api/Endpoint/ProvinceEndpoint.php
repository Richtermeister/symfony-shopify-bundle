<?php

namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class ProvinceEndpoint extends AbstractEndpoint
{
    /**
     * @param int $countryId
     * @param array $query
     * @return array|GenericResource[]
     */
    public function findByCountry($countryId, array $query = array())
    {
        $request = new GetJson('/admin/countries/' . $countryId . '/provinces.json', $query);
        $response = $this->send($request);
        return $this->createCollection($response->get('provinces'));
    }

    /**
     * @param int $countryId
     * @param array $query
     * @return int
     */
    public function countByCountry($countryId, array $query = array())
    {
        $request = new GetJson('/admin/countries/' . $countryId . '/provinces.json', $query);
        $response = $this->send($request);
        return $response->get('count');
    }

    /**
     * @param int $countryId
     * @param int $provinceId
     * @return GenericResource
     */
    public function findOne($countryId, $provinceId)
    {
        $request = new GetJson('/admin/countries/' . $countryId . '/provinces/' . $provinceId . '.json');
        $response = $this->send($request);
        return $this->createEntity($response->get('province'));
    }

    /**
     * @param int $countryId
     * @param int $provinceId
     * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $province
     * @return GenericResource
     */
    public function update($countryId, $provinceId, GenericResource $province)
    {
        $request = new PutJson('/admin/countries/' . $countryId . '/provinces/' . $provinceId . '.json', array('province' => $province->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('province'));
    }
}
