<?php

namespace CodeCloud\Bundle\ShopifyBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class ShopifyAdminUser implements UserInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string[]
     */
    private $roles = [];

    /**
     * @var string
     */
    private $authToken;

    /**
     * @param string $username
     * @param string[] $roles
     * @param string|null $authToken
     */
    public function __construct($username, array $roles, $authToken = null)
    {
        $this->username = $username;
        $this->roles = $roles;
        $this->authToken = $authToken;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->authToken;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
        $this->authToken = null;
    }
}
