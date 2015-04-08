<?php
namespace CodeCloud\Bundle\ShopifyBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ShopifyStore extends \Twig_Extension {

	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * @return string
	 */
	public function getName() {
		return 'shopifyStore';
	}

	/**
	 * @return array
	 */
	public function getGlobals() {
		$apiClient =  $this->container->get('codecloud_shopify.client');
		$store = $apiClient->getStore();

		if (! $store) {
			return array();
		}

		return array(
			'currentStoreName' => $store->getShopName(),
			'shopifyApiKey' => $this->container->getParameter('shopify_api_key')
		);
	}

	/**
	 * @return array
	 */
	public function getFunctions() {
		$apiClient =  $this->container->get('codecloud_shopify.client');
		$store = $apiClient->getStore();

		if (! $store) {
			return array();
		}

		$authParams = $this->container->get('codecloud_shopify.signer')->generateParams($store->getShopName());

		return array(
			new \Twig_SimpleFunction('embedded_link', function($uri, $uriParams = null) use ($authParams) {
				$params = array();

				foreach ($authParams as $key => $value) {
					$params[] = $key . '=' . $value;
				}

				$params = implode('&', $params);

				if ($uriParams) {
					$params .= '&' . $uriParams;
				}

				return '/embedded/' . $uri . '?' . $params;
			})
		);
	}

	/**
	 * @param ContainerInterface $container
	 */
	public function setContainer(ContainerInterface $container) {
		$this->container = $container;
	}
}