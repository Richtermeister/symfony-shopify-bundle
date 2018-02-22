<?php

namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class OrderRisksEndpoint extends AbstractEndpoint
{
    /**
     * @param int $orderId
     * @return array|GenericResource
     */
    public function findByOrder($orderId)
    {
        $request = new GetJson('/admin/orders/' . $orderId . '/risks.json');
        $response = $this->send($request);
        return $this->createCollection($response->get('risks'));
    }

    /**
     * @param int $orderId
     * @param int $orderRisksId
     * @return GenericResource
     */
    public function findOne($orderId, $orderRisksId)
    {
        $request = new GetJson('/admin/orders/' . $orderId . '/risks/' . $orderRisksId . '.json');
        $response = $this->send($request);
        return $this->createEntity($response->get('risk'));
    }

    /**
     * @param int $orderId
     * @param GenericResource $orderRisks
     * @return GenericResource
     */
    public function create($orderId, GenericResource $orderRisks)
    {
        $request = new PostJson('/admin/orders/' . $orderId . '/risks.json', $orderRisks);
        $response = $this->send($request);
        return $this->createEntity($response->get('risk'));
    }

    /**
     * @param int $orderId
     * @param int $orderRisksId
     * @param GenericResource $orderRisks
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function update($orderId, $orderRisksId, GenericResource $orderRisks)
    {
        $request = new PutJson('/admin/orders/' . $orderId . '/risks/' . $orderRisksId . '.json', array('risk' => $orderRisks->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('risk'));
    }

    /**
     * @param int $orderId
     * @param int $orderRisksId
     */
    public function delete($orderId, $orderRisksId)
    {
        $request = new DeleteParams('/admin/orders/' . $orderId . '/risks/' . $orderRisksId . '.json');
        $this->send($request);
    }
}
