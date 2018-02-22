<?php

namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class CarrierServiceEndpoint extends AbstractEndpoint
{
    /**
     * @return array|GenericResource[]
     */
    public function findAll()
    {
        $request = new GetJson('/admin/carrier_services.json');
        $response = $this->send($request);
        return $this->createCollection($response->get('carrier_services'));
    }

    /**
     * @param int $carrierServiceId
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function findOne($carrierServiceId)
    {
        $request = new GetJson('/admin/carrier_services/' . $carrierServiceId . '.json');
        $response = $this->send($request);
        return $this->createEntity($response->get('carrier_service'));
    }

    /**
     * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $carrierService
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function create(GenericResource $carrierService)
    {
        $request = new PostJson('/admin/carrier_services.json', array('carrier_service' => $carrierService->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('carrier_service'));
    }

    /**
     * @param int $carrierServiceId
     * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $carrierService
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function update($carrierServiceId, GenericResource $carrierService)
    {
        $request = new PostJson('/admin/carrier_services/' . $carrierServiceId . '.json', array('carrier_service' => $carrierService->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('carrier_service'));
    }

    /**
     * @param int $carrierServiceId
     */
    public function delete($carrierServiceId)
    {
        $request = new DeleteParams('/admin/carrier_services/' . $carrierServiceId . '.json');
        $this->send($request);
    }
}
