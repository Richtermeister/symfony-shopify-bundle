<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\Exception\FailedRequestException;
use CodeCloud\Bundle\ShopifyBundle\Api\ShopifyApiClient;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;
use Psr\Http\Message\RequestInterface;

abstract class ResourceMapper
{
	/**
	 * @var ShopifyApiClient
	 */
	protected $api;

	/**
	 * @param ShopifyApiClient $apiClient
	 */
	public function __construct(ShopifyApiClient $apiClient)
	{
		$this->api = $apiClient;
	}

	/**
	 * @param RequestInterface $request
	 * @return \CodeCloud\Bundle\ShopifyBundle\Api\Response\ResponseInterface
	 * @throws FailedRequestException
	 */
	protected function send(RequestInterface $request)
	{
		$response = $this->api->process($request);

		if (! $response->successful()) {
			throw new FailedRequestException('Failed request. ' . $response->getHttpResponse()->getReasonPhrase());
		}

		return $response;
	}

	/**
	 * @param RequestInterface $request
	 * @param string $rootElement
	 * @return array
	 * @throws FailedRequestException
	 */
	protected function sendPaged(RequestInterface $request, $rootElement)
	{
		return $this->api->processPaged($request, $rootElement);
	}

	/**
	 * @param array $items
	 * @param GenericResource|null $prototype
	 * @return array
	 */
	protected function createCollection($items, GenericResource $prototype = null)
	{
		if (! $prototype) {
			$prototype = new GenericResource();
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
	 * @return GenericResource
	 */
	protected function createEntity($data)
	{
		$entity = new GenericResource();
		$entity->hydrate($data);

		return $entity;
	}
}
