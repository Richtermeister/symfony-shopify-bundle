<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Request;

use GuzzleHttp\Psr7\Request;

class GetParams extends Request
{
	/**
	 * @param string $url
	 * @param array $params
	 */
	public function __construct($url, array $params = array())
	{
        if (!empty($params)) {
            $url .= '?'.http_build_query($params);
        }

		parent::__construct('GET', $url);
	}
}
