<?php
namespace CodeCloud\Bundle\ShopifyBundle\Events;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\Exception\FailedRequestException;
use CodeCloud\Bundle\ShopifyBundle\Api\ShopifyApiClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Router;

class OAuthRequestListener
{
	/**
	 * @var array
	 */
	private $config;

	/**
	 * @var ShopifyApiClient
	 */
	private $shopifyClient;

	/**
	 * @var Router
	 */
	private $router;

	/**
	 * @param array $config
	 * @param Router $router
	 * @param ShopifyApiClient $shopifyClient
	 */
	public function __construct($config, Router $router, ShopifyApiClient $shopifyClient)
	{
		$this->config        = $config;
		$this->router        = $router;
		$this->shopifyClient = $shopifyClient;
	}

	/**
	 * @param GetResponseEvent $event
	 */
	public function onKernelRequest(GetResponseEvent $event)
	{
		if (! $interceptMethod = $this->getInterceptMethod($event->getRequest())) {
			return;
		}
		$this->$interceptMethod($event);
	}

	/**
	 * @param GetResponseEvent $event
	 */
	protected function interceptOAuthStep1(GetResponseEvent $event)
	{
		$storeName = $event->getRequest()->get('shop');

		$step2Url = $this->router->generate($this->config['oauth']['step2'], array('scope' => $this->config['oauth']['scope']), Router::ABSOLUTE_URL);

		$shopifyEndpoint = 'https://%s/admin/oauth/authorize?client_id=%s&scope=%s&redirect_uri=%s';
		$url = sprintf($shopifyEndpoint, $storeName, $this->config['credentials']['api_key'], $this->config['oauth']['scope'], $step2Url);

		$response = new Response('', 302, array(
			'Location' => $url
		));

		$event->setResponse($response);
	}

	/**
	 * @param GetResponseEvent $event
	 * @throws FailedRequestException
	 */
	protected function interceptOAuthStep2(GetResponseEvent $event)
	{
		$request = $event->getRequest();
		$authCode = $request->get('code');
		$storeName = $request->get('shop');

		$params = array('body' => array(
			'client_id'     => $this->config['credentials']['api_key'],
			'client_secret' => $this->config['credentials']['shared_secret'],
			'code'          => $authCode
		));

		$apiRequest = $this->shopifyClient->http()->createRequest('POST', 'https://' . $storeName . '/admin/oauth/access_token', $params);
		$response = $this->shopifyClient->http()->send($apiRequest);

		if ($response->getStatusCode() != 200) {
			throw new FailedRequestException((string)$response);
		}

		$request->attributes->set('access_token', $response->json(array('object' => true))->access_token);
	}

	/**
	 * @param Request $request
	 * @return null|string
	 */
	protected function getInterceptMethod(Request $request)
	{
		switch ($request->get('_route')) {
			case $this->config['oauth']['step1'];
				$step = 1;
				break;
			case $this->config['oauth']['step2'];
				$step = 2;
				break;
			default:
				return null;
		}
		return 'interceptOAuthStep' . $step;
	}
}