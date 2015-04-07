<?php
namespace CodeCloud\Bundle\ShopifyBundle\Auth\OutgoingApiRequest;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\ModifyableRequest;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\RequestModifier;

class ShopNameRequestModifier implements RequestModifier
{

	/**
	 * @var string
	 */
	private $shopName;

	/**
	 * @param string $shopName
	 */
	public function __construct($shopName)
	{
		$this->shopName = $shopName;
	}

	/**
	 * @param ModifyableRequest $request
	 */
	public function modify(ModifyableRequest $request)
	{
		$url = $request->getUrl();
		$url = 'https://' . $this->shopName . $url;
		$request->setUrl($url);
	}
}