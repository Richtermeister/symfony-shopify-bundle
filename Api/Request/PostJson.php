<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Request;

use GuzzleHttp\Stream\Stream;

class PostJson extends ModifyableRequest
{
	/**
	 * @param string $url
	 * @param array|string $postData
	 * @param array $params
	 */
	public function __construct($url, $postData = null, array $params = array())
	{
		if ($postData = $postData !== null ? json_encode($postData, JSON_PRETTY_PRINT) : null) {
			$postData = Stream::factory($postData);
		}

		parent::__construct('POST', $url, $params, $postData);

		$this->setHeader('Content-type', 'application/json');
	}
}