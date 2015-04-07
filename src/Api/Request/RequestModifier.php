<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Request;

interface RequestModifier
{
	public function modify(ModifyableRequest $request);
}