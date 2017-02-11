<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Response;

use Psr\Http\Message\ResponseInterface as PsrResponse;

class HtmlResponse implements ResponseInterface
{
	/**
	 * @var PsrResponse
	 */
	private $response;

	/**
	 * @param PsrResponse $response
	 */
	public function __construct(PsrResponse $response)
	{
		$this->response = $response;
	}

	/**
	 * @return PsrResponse
	 */
	public function getHttpResponse()
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
		return preg_match('/^2[\d]{2,}/', $this->getHttpResponse()->getStatusCode());
	}
}
