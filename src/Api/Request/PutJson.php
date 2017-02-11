<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Request;

use GuzzleHttp\Psr7\Request;

class PutJson extends Request
{
	/**
	 * @param string $url
	 * @param array|string $postData
	 * @param array $headers
	 */
	public function __construct($url, $postData = null, array $headers = array())
	{
        if ($postData !== null) {
            $postData = json_encode($postData, JSON_PRETTY_PRINT);
        }

        if (!empty($params)) {
            $url .= '?'.http_build_query($params);
        }

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

		parent::__construct('PUT', $url, $headers, $postData);
	}
}