<?php
namespace CodeCloud\Bundle\ShopifyBundle\Auth\Provider;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ShopifyStoreProvider implements UserProviderInterface
{
	/**
	 * @var string
	 */
	private $shopifyStoreEntityClassName;

	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * @param EntityManager $entityManager
	 * @param array $shopifyConfig
	 */
	public function __construct(EntityManager $entityManager, array $shopifyConfig)
	{
		if (empty($shopifyConfig['store_entity'])) {
			throw new \InvalidArgumentException('codecloud_shopify.store_entity configuration option is missing');
		}

		$this->entityManager = $entityManager;
		$this->shopifyStoreEntityClassName = $shopifyConfig['store_entity'];
	}

	/**
	 * @param string $username
	 * @return \CodeCloud\Bundle\ShopifyBundle\Entity\ShopifyStoreInterface
	 */
	public function loadUserByUsername($username)
	{
		/** @var \CodeCloud\Bundle\ShopifyBundle\Entity\ShopifyStoreRepositoryInterface $repository */
		$repository = $this->entityManager->getRepository($this->shopifyStoreEntityClassName);
		return $repository->findOneByShopName($username);
	}

	/**
	 * @return UserInterface
	 */
	public function refreshUser(UserInterface $user)
	{
		return $this->loadUserByUsername($user->getUsername());
	}

	/**
	 * @param string $class
	 * @return bool
	 */
	public function supportsClass($class)
	{
		return $class === $this->shopifyStoreEntityClassName;
	}
}