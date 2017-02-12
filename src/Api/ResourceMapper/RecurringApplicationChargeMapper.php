<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;

class RecurringApplicationChargeMapper extends AbstractResourceMapper
{
	/**
	 * @param \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource $recurringApplicationCharge
	 * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
	 */
	public function create(GenericResource $recurringApplicationCharge)
	{
		$request = new PostJson('/admin/recurring_application_charges.json', array('recurring_application_charge' => $recurringApplicationCharge->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('recurring_application_charge'));
	}

	/**
	 * @param int $recurringApplicationChargeId
	 * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
	 */
	public function findOne($recurringApplicationChargeId)
	{
		$request = new GetJson('/admin/recurring_application_charges/' . $recurringApplicationChargeId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('recurring_application_charge'));
	}

	/**
	 * @param array $query
	 * @return array|GenericResource[]
	 */
	public function findAll(array $query = array())
	{
		$request = new GetJson('/admin/recurring_application_charges.json', $query);
		$response = $this->send($request);
		return $this->createCollection($response->get('recurring_application_charges'));
	}

	/**
	 * @param int $recurringApplicationChargeId
	 * @return \CodeCloud\Bundle\ShopifyBundle\Api\GenericResource
	 */
	public function activate($recurringApplicationChargeId)
	{
		$request = new PostJson('/admin/recurring_application_charges/' . $recurringApplicationChargeId . '/activate.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('recurring_application_charge'));
	}

	/**
	 * @param int $recurringApplicationChargeId
	 */
	public function delete($recurringApplicationChargeId)
	{
		$request = new DeleteParams('/admin/recurring_application_charges/' . $recurringApplicationChargeId . '.json');
		$this->send($request);
	}
}