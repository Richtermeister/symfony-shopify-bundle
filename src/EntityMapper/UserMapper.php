<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;

class UserMapper extends EntityMapper
{
	/**
	 * @return array|GenericEntity[]
	 */
	public function findAll()
	{
		$request = new GetJson('/admin/users.json');
		$response = $this->send($request);
		return $this->createCollection($response->get('users'));
	}

	/**
	 * @param int $userId
	 * @return GenericEntity
	 */
	public function findOne($userId)
	{
		$request = new GetJson('/admin/users/' . $userId . '.json');
		$response = $this->send($request);
		return $this->createEntity($response->get('user'));
	}
}