<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Request;

class DeleteParams extends ModifyableRequest
{
	/**
	 * @param string $url
	 * @param array $params
	 */
	public function __construct($url, array $params = array())
	{
		parent::__construct('DELETE', $params);
	}
}