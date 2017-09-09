<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class ApplicationChargeEndpoint extends AbstractEndpoint
{
    /**
     * @param array $query
     * @return GenericResource[]
     */
    public function findAll(array $query = array())
    {
        $request = new GetParams('/admin/application_charges.json', $query);
        $response = $this->send($request);
        return $this->createCollection($response->get('application_charges'));
    }

    /**
     * @param int $applicationChargeId
     * @return GenericResource
     */
    public function findOne($applicationChargeId)
    {
        $request = new GetParams('/admin/application_charges/' . $applicationChargeId . '.json');
        $response = $this->send($request);
        return $this->createEntity($response->get('application_charge'));
    }

    /**
     * @param GenericResource $applicationCharge
     * @return GenericResource
     */
    public function create(GenericResource $applicationCharge)
    {
        $request = new PostJson('/admin/application_charges.json', array('application_charge' => $applicationCharge->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('application_charge'));
    }

    /**
     * @param int $applicationChargeId
     */
    public function activate($applicationChargeId)
    {
        $request = new PostJson('/admin/application_charges/' . $applicationChargeId . '/activate.json', null);
        $this->send($request);
    }
}
