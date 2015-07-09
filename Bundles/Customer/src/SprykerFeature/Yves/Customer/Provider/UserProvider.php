<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Customer\Provider;

use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Generated\Zed\Ide\AutoCompletion;
use Generated\Shared\Transfer\CustomerTransfer;

class UserProvider implements UserProviderInterface
{

    /** @var FactoryInterface  */
    protected $factory;

    /** @var AutoCompletion */
    protected $locator;

    /** @var SessionInterface */
    protected $session;

    /**
     * @param FactoryInterface $factory
     * @param LocatorLocatorInterface $locator
     * @param SessionInterface $session
     */
    public function __construct(FactoryInterface $factory, LocatorLocatorInterface $locator, SessionInterface $session)
    {
        $this->factory = $factory;
        $this->locator = $locator;
        $this->session = $session;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function loadUserByUsername($username)
    {
        $user = $this->session->get($this->getKey($username));
        if (!$user) {
            $user = $this->fetchUser($username);
            $this->session->set($this->getKey($username), $user);
        }

        return new User(
            $user['username'],
            $user['password'],
            $user['roles'],
            true,
            true,
            true,
            true
        );
    }

    /**
     * @param string $username
     */
    public function logout($username)
    {
        $this->session->remove($this->getKey($username));
    }

    /**
     * @param string $username
     *
     * @return string
     */
    protected function getKey($username)
    {
        return 'userdata:' . $username;
    }

    /**
     * @param string $username
     *
     * @return array
     */
    protected function fetchUser($username)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail($username);
        $customerTransfer = $this->locator->customer()->client()->getCustomer($customerTransfer);

        return [
            'username' => $customerTransfer->getEmail(),
            'password' => $customerTransfer->getPassword(),
            'roles' => ['ROLE_USER'],
        ];
    }

    /**
     * @param UserInterface $user
     *
     * @return User
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }

}
