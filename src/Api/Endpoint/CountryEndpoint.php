<?php

namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class CountryEndpoint extends AbstractEndpoint
{
    /**
     * @return array|GenericResource[]
     */
    public function findAll()
    {
        $request = new GetJson('/admin/countries.json');
        $response = $this->send($request);
        return $this->createCollection($response->get('countries'));
    }

    /**
     * @return int
     */
    public function countAll()
    {
        $request = new GetJson('/admin/countries/count.json');
        $response = $this->send($request);
        return $response->get('count');
    }

    /**
     * @param int $countryId
     * @return GenericResource
     */
    public function findOne($countryId)
    {
        $request = new GetJson('/admin/countries/' . $countryId . '.json');
        $response = $this->send($request);
        return $this->createEntity($response->get('country'));
    }

    /**
     * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $country
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function create(GenericResource $country)
    {
        $request = new PostJson('/admin/countries.json', array('country' => $country->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('country'));
    }

    /**
     * @param int $countryId
     * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $country
     * @return GenericResource
     */
    public function update($countryId, GenericResource $country)
    {
        $request = new PutJson('/admin/countries/' . $countryId . '.json', array('country' => $country->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('country'));
    }

    /**
     * @param int $countryId
     */
    public function delete($countryId)
    {
        $request = new DeleteParams('/admin/countries/' . $countryId . '.json');
        $this->send($request);
    }
}
