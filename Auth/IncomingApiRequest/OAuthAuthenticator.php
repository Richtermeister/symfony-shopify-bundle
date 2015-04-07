<?php
namespace CodeCloud\Bundle\ShopifyBundle\Auth\IncomingApiRequest;

use CodeCloud\Bundle\ShopifyBundle\Api\ShopifyApiClient;
use CodeCloud\Bundle\ShopifyBundle\Auth\HmacSignature;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class OAuthAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface {

	/**
	 * @var HmacSignature
	 */
	private $signatureVerifier;

	/**
	 * @var ShopifyApiClient
	 */
	private $shopifyClient;

	/**
	 * @param HmacSignature $signatureVerifier
	 * @param ShopifyApiClient $shopifyClient
	 */
	public function __construct(HmacSignature $signatureVerifier, ShopifyApiClient $shopifyClient) {
		$this->signatureVerifier = $signatureVerifier;
		$this->shopifyClient     = $shopifyClient;
	}

	/**
	 * @param Request $request
	 * @param string $providerKey
	 * @return PreAuthenticatedToken
	 */
	public function createToken(Request $request, $providerKey) {
		$credentials = array();

		foreach (array('shop', 'hmac', 'timestamp') as $requiredParam) {
			if (! $value = $request->get($requiredParam, $request->get('amp;' . $requiredParam))) {
				throw new BadCredentialsException($requiredParam . ' is missing.');
			}

			$credentials[str_replace('amp;', '', $requiredParam)] = $value;
		}

		return new PreAuthenticatedToken(
			'anon.',
			$credentials,
			$providerKey
		);
	}

	/**
	 * @param TokenInterface $token
	 * @param UserProviderInterface $storeProvider
	 * @param $providerKey
	 * @return PreAuthenticatedToken
	 */
	public function authenticateToken(TokenInterface $token, UserProviderInterface $storeProvider, $providerKey) {
		$credentials = $token->getCredentials();

		//verify the shopify signature
		if (! $this->signatureVerifier->isValid($credentials['hmac'], $credentials)) {
			throw new BadCredentialsException('Invalid signature');
		}

		$store = $storeProvider->loadUserByUsername($credentials['shop']);

		//configure the API client to authenticate all outgoing requests with the shopify store's credentials
		$this->shopifyClient->setShopifyStore($store);

		return new PreAuthenticatedToken(
			$store,
			$credentials,
			$providerKey,
			$store->getRoles()
		);
	}

	/**
	 * @param TokenInterface $token
	 * @param string $providerKey
	 * @return bool
	 */
	public function supportsToken(TokenInterface $token, $providerKey) {
		return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
	}

	/**
	 * @param Request $request
	 * @param AuthenticationException $exception
	 * @return Response
	 */
	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
		return new Response("API Authentication Failed.", 403);
	}
}