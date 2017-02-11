<?php
namespace CodeCloud\Bundle\ShopifyBundle\Security;

use CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreInterface;
use CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ShopifyStoreProvider implements UserProviderInterface
{
    /**
     * @var ShopifyStoreManagerInterface
     */
	private $shops;

    /**
     * @param ShopifyStoreManagerInterface $shops
     */
    public function __construct(ShopifyStoreManagerInterface $shops)
    {
        $this->shops = $shops;
    }

    /**
	 * @param string $username
	 * @return \CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreInterface
	 */
	public function loadUserByUsername($username)
	{
		return $this->shops->findStoreByName($username);
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
