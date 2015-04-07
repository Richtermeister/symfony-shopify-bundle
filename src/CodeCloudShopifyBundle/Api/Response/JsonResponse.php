<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Response;

use GuzzleHttp\Exception\ParseException;
use GuzzleHttp\Message\Response;

class JsonResponse implements ResponseInterface
{
	/**
	 * @var array
	 */
	private $decoded;

	/**
	 * @var Response
	 */
	private $response;

	/**
	 * @param Response $response
	 */
	public function __construct(Response $response)
	{
		$this->response = $response;
	}

	/**
	 * @return Response
	 */
	public function getGuzzleResponse()
	{
		return $this->response;
	}

	/**
	 * Access elements of the JSON response using dot notation
	 * @param null $item
	 * @param null $default
	 * @return mixed
	 */
	public function get($item = null, $default = null)
	{
		if (is_null($item)) {
			return $default;
		}

		$decoded = $this->getDecodedJson();

		if (array_key_exists($item, $decoded)) {
			return $decoded[$item];
		}

		foreach (explode('.', $item) as $segment) {
			if (! is_array($decoded) || ! array_key_exists($segment, $decoded)) {
				return $default;
			}

			$decoded = $decoded[$segment];
		}

		return $decoded;
	}

	/**
	 * @return bool
	 */
	public function successful()
	{
		return preg_match('/^2[\d]{2,}/', $this->getGuzzleResponse()->getStatusCode());
	}

	/**
	 * @return array
	 */
	private function getDecodedJson()
	{
		if (! $this->decoded) {
			try {
				$this->decoded = $this->response->json();
			} catch (ParseException $e) {
				return array();
			}
		}
		return $this->decoded;
	}
}