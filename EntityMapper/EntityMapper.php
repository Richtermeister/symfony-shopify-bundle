<?php
namespace CodeCloud\Bundle\ShopifyBundle\EntityMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\Exception\FailedRequestException;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\ModifyableRequest;
use CodeCloud\Bundle\ShopifyBundle\Api\ShopifyApiClient;
use CodeCloud\Bundle\ShopifyBundle\Entity\GenericEntity;

abstract class EntityMapper
{
	/**
	 * @var ShopifyApiClient
	 */
	protected $api;

	/**
	 * @var string
	 */
	private $storeName;

	/**
	 * @param ShopifyApiClient $apiClient
	 */
	public function __construct(ShopifyApiClient $apiClient)
	{
		$this->api = $apiClient;
	}

	/**
	 * @param string $storeName
	 */
	public function setStoreName($storeName)
	{
		$this->storeName = $storeName;
	}

	/**
	 * @param ModifyableRequest $request
	 * @return \CodeCloud\Bundle\ShopifyBundle\Api\Response\ResponseInterface
	 * @throws FailedRequestException
	 */
	protected function send(ModifyableRequest $request)
	{
		$response = $this->api->process($request);

		if (! $response->successful()) {
			throw new FailedRequestException('Failed request. ' . $response->getGuzzleResponse());
		}

		return $response;
	}

	/**
	 * @param ModifyableRequest $request
	 * @param string $rootElement
	 * @return array
	 * @throws FailedRequestException
	 */
	protected function sendPaged(ModifyableRequest $request, $rootElement)
	{
		return $this->api->processPaged($request, $rootElement);
	}

	/**
	 * @param array $items
	 * @param null $prototype
	 * @return array
	 */
	protected function createCollection($items, $prototype = null)
	{
		if (! $prototype) {
			$prototype = new GenericEntity();
		}

		$collection = array();

		foreach ((array)$items as $item) {
			$newItem = clone $prototype;
			$newItem->hydrate($item);
			$collection[] = $newItem;
		}

		return $collection;
	}

	/**
	 * @param array $data
	 * @return GenericEntity
	 */
	protected function createEntity($data)
	{
		$entity = new GenericEntity();
		$entity->hydrate($data);
		return $entity;
	}
}