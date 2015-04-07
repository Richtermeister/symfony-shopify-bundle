<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Request;

use GuzzleHttp\Stream\Stream;

class PutJson extends ModifyableRequest
{
	/**
	 * @param string $url
	 * @param array|string $postData
	 * @param array $headers
	 */
	public function __construct($url, $postData = null, array $headers = array())
	{
		if ($postData = $postData !== null ? json_encode($postData, JSON_PRETTY_PRINT) : null) {
			$postData = Stream::factory($postData);
		}

		parent::__construct('PUT', $url, $headers, $postData);

		$this->setHeader('Content-type', 'application/json');
	}
}