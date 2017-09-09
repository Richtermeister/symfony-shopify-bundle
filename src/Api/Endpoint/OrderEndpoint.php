<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PutJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class OrderEndpoint extends AbstractEndpoint
{
    /**
     * @param array $query
     * @return array|\CodeCloud\Bundle\ShopifyBundle\Api\GenericResource[]
     */
    public function findAll(array $query = array())
    {
        $request = new GetJson('/admin/orders.json', $query);
        $response = $this->sendPaged($request, 'orders');
        return $this->createCollection($response);
    }

    /**
     * @param int $orderId
     * @param array $fields
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function findOne($orderId, array $fields = array())
    {
        $params = $fields ? array('fields' => implode(',', $fields)) : array();
        $request = new GetJson('/admin/orders/' . $orderId . '.json', $params);
        $response = $this->send($request);
        return $this->createEntity($response->get('order'));
    }

    /**
     * @param array $query
     * @return int
     */
    public function countAll(array $query = array())
    {
        $request = new GetJson('/admin/orders/count.json', $query);
        $response = $this->send($request);
        return $response->get('count');
    }

    /**
     * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $order
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function create(GenericResource $order)
    {
        $request = new PostJson('/admin/orders.json', array('order' => $order->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('order'));
    }

    /**
     * @param int $orderId
     * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $order
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
     */
    public function update($orderId, GenericResource $order)
    {
        $request = new PutJson('/admin/orders/' . $orderId . '.json', array('order' => $order->toArray()));
        $response = $this->send($request);
        return $this->createEntity($response->get('order'));
    }

    /**
     * @param int $orderId
     */
    public function delete($orderId)
    {
        $request = new DeleteParams('/admin/orders/' . $orderId . '.json');
        $this->send($request);
    }

    /**
     * @param int $orderId
     */
    public function close($orderId)
    {
        $request = new PostJson('/admin/orders/' . $orderId . '/close.json');
        $this->send($request);
    }

    /**
     * @param int $orderId
     */
    public function open($orderId)
    {
        $request = new PostJson('/admin/orders/' . $orderId . '/open.json');
        $this->send($request);
    }

    /**
     * @param int $orderId
     * @param array $options
     */
    public function cancel($orderId, array $options = array())
    {
        $request = new PostJson('/admin/orders/' . $orderId . '/cancel.json', $options);
        $this->send($request);
    }
}
