<?php
namespace CodeCloud\Bundle\ShopifyBundle\Auth\OutgoingApiRequest;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\ModifyableRequest;
use CodeCloud\Bundle\ShopifyBundle\Api\Request\RequestModifier;

class AccessTokenRequestModifier implements RequestModifier
{

	/**
	 * @var string
	 */
	private $accessToken;

	/**
	 * @param string $accessToken
	 */
	public function __construct($accessToken)
	{
		$this->accessToken = $accessToken;
	}

	/**
	 * @param ModifyableRequest $request
	 */
	public function modify(ModifyableRequest $request)
	{
		$request->addHeader('X-Shopify-Access-Token', $this->accessToken);
	}
}