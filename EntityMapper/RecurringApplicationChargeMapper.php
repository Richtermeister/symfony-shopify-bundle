<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\DeleteParams;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\PostJson;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

class RecurringApplicationChargeMapper extends EntityMapper
{
	/**
	 * @param GenericEntity $recurringApplicationCharge
	 * @return GenericEntity
	 */
	public function create(GenericEntity $recurringApplicationCharge)
	{
		$request = new PostJson('/admin/recurring_application_charges.json', array('recurring_application_charge' => $recurringApplicationCharge->toArray()));
		$response = $this->send($request);
		return $this->createEntity($response->get('recurring_application_charge'));
	}

	/**
	 * @param int $recurringApplicationChargeId
	 * @return GenericEntity
	 */
	public function findOne($recurringApplicationChargeId)
	{
		$request = new GetJson('/admin/recurring_application_charges/' . $recurringApplicationChargeId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('recurring_application_charge'));
	}

	/**
	 * @param array $query
	 * @return array|GenericEntity[]
	 */
	public function findAll(array $query = array())
	{
		$request = new GetJson('/admin/recurring_application_charges.json', $query);
		$response = $this->send($request);
		return $this->createCollection($response->get('recurring_application_charges'));
	}

	/**
	 * @param int $recurringApplicationChargeId
	 * @return GenericEntity
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