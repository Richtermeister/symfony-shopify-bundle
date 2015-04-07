<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Response;

use GuzzleHttp\Message\Response;

class HtmlResponse implements ResponseInterface
{
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
	 * @param null $item
	 * @param null $default
	 * @return mixed
	 */
	public function get($item = null, $default = null)
	{
		return $this->response->getBody()->getContents();
	}

	/**
	 * @return bool
	 */
	public function successful()
	{
		return preg_match('/^2[\d]{2,}/', $this->getGuzzleResponse()->getStatusCode());
	}
}