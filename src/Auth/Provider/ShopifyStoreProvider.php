<?php
namespace CodeCloud\Bundle\ShopifyBundle\Auth\Provider;

use CodeCloud\Bundle\ShopifyBundle\Entity\ShopifyStoreInterface;
use CodeCloud\Bundle\ShopifyBundle\Entity\ShopifyStoreRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ShopifyStoreProvider implements UserProviderInterface
{
    /**
     * @var ShopifyStoreRepositoryInterface
     */
	private $shops;

    /**
     * @param ShopifyStoreRepositoryInterface $shops
     */
    public function __construct(ShopifyStoreRepositoryInterface $shops)
    {
        $this->shops = $shops;
    }

    /**
	 * @param string $username
	 * @return ShopifyStoreInterface
	 */
	public function loadUserByUsername($username)
	{
		return $this->shops->findOneByShopName($username);
	}

    /**
     * @param UserInterface $user
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
		return $class instanceof ShopifyStoreInterface;
	}
}
