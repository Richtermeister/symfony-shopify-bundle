<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\GetJson;

class UserMapper extends AbstractResourceMapper
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