<?php

namespace CodeCloud\Bundle\ShopifyBundle\Security;

use CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreManagerInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ShopifyAdminUserProvider implements UserProviderInterface
{
    const ROLE_SHOPIFY_ADMIN = 'ROLE_SHOPIFY_ADMIN';

    /**
     * @var ShopifyStoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ShopifyStoreManagerInterface $storeManager
     */
    public function __construct(ShopifyStoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    public function loadUserByUsername($username)
    {
        if (!$this->storeManager->storeExists($username)) {
            throw new UsernameNotFoundException();
        }

        return new ShopifyAdminUser($username, [self::ROLE_SHOPIFY_ADMIN]);
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass($class)
    {
        return $class instanceof ShopifyAdminUser;
    }
}
