<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Response;

use GuzzleHttp\Message\Response;

interface ResponseInterface
{
	/**
	 * @return mixed
	 */
	public function successful();

	/**
	 * Get the body of the response.
	 * If item is specified, this can be used to drill down into the response object and retrieve specific items within it
	 * @param string $item
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($item = null, $default = null);

	/**
	 * @return Response
	 */
	public function getGuzzleResponse();
}