<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Request;

class GetJson extends ModifyableRequest
{
	/**
	 * @param string $url
	 * @param array $params
	 */
	public function __construct($url, array $params = array())
	{
		parent::__construct('GET', $url, $params);

		$this->setHeader('Content-type', 'application/json');
	}
}