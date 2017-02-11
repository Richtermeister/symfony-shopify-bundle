<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api;

use CodeCloud\Bundle\ShopifyBundle\Api\Response\ErrorResponse;
use CodeCloud\Bundle\ShopifyBundle\Api\Response\HtmlResponse;
use CodeCloud\Bundle\ShopifyBundle\Api\Response\JsonResponse;
use CodeCloud\Bundle\ShopifyBundle\Entity\ShopifyStoreInterface;
use GuzzleHttp\ClientInterface as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;

class ShopifyApiClient
{

	/**
	 * @var HttpClient
	 */
	private $http;

	/**
	 * @var ShopifyStoreInterface
	 */
	private $shopifyStore;

	/**
	 * @param HttpClient $httpClient
	 */
	public function __construct(HttpClient $httpClient)
	{
		$this->http = $httpClient;
	}

	/**
	 * @param RequestInterface $request
	 * @return \CodeCloud\Bundle\ShopifyBundle\Api\Response\ResponseInterface
	 */
	public function process(RequestInterface $request)
	{
		$guzzleResponse = $this->http->send($request);

		try {
			switch ($request->getHeaderLine('Content-type')) {
				case 'application/json':
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
	 * @param RequestInterface $request
	 * @param string $rootElement
	 * @param array $params
	 * @return array
	 */
	public function processPaged(RequestInterface $request, $rootElement, array $params = array())
	{
		if (empty($params['page'])) {
			$params['page'] = 1;
		}

		if (empty($params['limit'])) {
			$params['limit'] = 250;
		}

		$allResults = array();

		do {
			$requestUrl = $request->getUri();
			$paramDelim = strstr($requestUrl, '?') ? '&' : '?';

            $pagedRequest = $request->withUri(new Uri($requestUrl . $paramDelim . http_build_query($params)));

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
	 * @return ShopifyStoreInterface
	 */
	public function getStore()
	{
		return $this->shopifyStore;
	}
}