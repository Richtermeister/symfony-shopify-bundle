<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Request;

use GuzzleHttp\Psr7\Request;

class PostJson extends Request
{
	/**
	 * @param string $url
	 * @param array|string $postData
	 * @param array $params
	 */
	public function __construct($url, $postData = null, array $params = array())
	{
		if ($postData !== null) {
			$postData = \GuzzleHttp\json_encode($postData, JSON_PRETTY_PRINT);
		}

        if (!empty($params)) {
            $url .= '?'.http_build_query($params);
        }

        $headers = [
            'Content-Type' => 'application/json',
        ];

		parent::__construct('POST', $url, $headers, $postData);
	}
}
