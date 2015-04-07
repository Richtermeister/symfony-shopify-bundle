<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\ModifyableRequest;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\RequestModifier;
use CodeCloud\Bundle\ShopifyBundle\Api\Response\ErrorResponse;
use CodeCloud\Bundle\ShopifyBundle\Api\Response\HtmlResponse;
use CodeCloud\Bundle\ShopifyBundle\Api\Response\JsonResponse;
use CodeCloud\Bundle\ShopifyBundle\Auth\OutgoingApiRequest\AccessTokenRequestModifier;
use CodeCloud\Bundle\ShopifyBundle\Auth\OutgoingApiRequest\ShopNameRequestModifier;
use CodeCloud\Bundle\ShopifyBundle\Entity\ShopifyStoreInterface;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\Request;

class ShopifyApiClient
{

	/**
	 * @var HttpClient
	 */
	private $http;

	/**
	 * @var array|RequestModifier[]
	 */
	private $modifiers = array();

	/**
	 * @param HttpClient $httpClient
	 */
	public function __construct(HttpClient $httpClient)
	{
		$this->http = $httpClient;
	}

	/**
	 * @param RequestModifier $modifier
	 */
	public function addModifier(RequestModifier $modifier)
	{
		$this->modifiers[] = $modifier;
	}

	/**
	 * Runs all registered modifiers
	 * @param Request $request
	 * @return Request
	 */
	public function runModifiers(Request $request)
	{
		foreach ($this->modifiers as $modifier) {
			$modifier->modify($request);
		}
	}

	/**
	 * @param ModifyableRequest $request
	 * @return \GuzzleHttp\Message\Response|\CodeCloud\Bundle\ShopifyBundle\Api\Response\ResponseInterface
	 */
	public function process(ModifyableRequest $request)
	{
		$this->runModifiers($request);

		$guzzleResponse = $this->http->send($request);

		try {
			switch (true) {
				case $request->getHeader('Content-type', 'application/json'):
					$response = new JsonResponse($guzzleResponse);
					break;
				default:
					$response = new HtmlResponse($guzzleResponse);
			}
		} catch (ClientException $e) {
			$response = new ErrorResponse($guzzleResponse, $e);
		}

		return $response;
	}

	/**
	 * Loop through a set of API results that are available in pages, returning the full resultset as one array
	 * @param ModifyableRequest $request
	 * @param string $rootElement
	 * @param array $params
	 * @return array
	 */
	public function processPaged(ModifyableRequest $request, $rootElement, array $params = array())
	{
		if (empty($params['page'])) {
			$params['page'] = 1;
		}

		if (empty($params['limit'])) {
			$params['limit'] = 250;
		}

		$allResults = array();

		do {
			$pagedRequest = clone $request;

			$requestUrl = $request->getUrl();
			$paramDelim = strstr($requestUrl, '?') ? '&' : '?';

			$pagedRequest->setUrl($requestUrl . $paramDelim . http_build_query($params));

			$response = $this->process($pagedRequest);

			$root = $response->get($rootElement);

			if ($pageResults = empty($root) ? false : $root) {
				$allResults = array_merge($allResults, $pageResults);
			}

			$params['page']++;

		} while ($pageResults);

		return $allResults;
	}

	/**
	 * @return HttpClient
	 */
	public function http()
	{
		return $this->http;
	}

	/**
	 * @param ShopifyStoreInterface $shopifyStore
	 */
	public function setShopifyStore(ShopifyStoreInterface $shopifyStore)
	{
		$this->addModifier(new ShopNameRequestModifier($shopifyStore->getShopName()));
		$this->addModifier(new AccessTokenRequestModifier($shopifyStore->getAccessToken()));
	}
}