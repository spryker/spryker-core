<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\UserTransfer;

/**
 * @method AuthDependencyContainer getDependencyContainer()
 */
class AuthFacade extends AbstractFacade
{

    /**
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function login($username, $password)
    {
        return $this->getDependencyContainer()
            ->createAuthModel()
            ->authenticate($username, $password);
    }

    /**
     * @return bool
     */
    public function logout()
    {
        return $this->getDependencyContainer()
            ->createAuthModel()
            ->logout();
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isAuthorized($token)
    {
        return $this->getDependencyContainer()
            ->createAuthModel()
            ->isAuthorized($token);
    }

    /**
     * @return bool
     */
    public function hasCurrentUser()
    {
        return $this->getDependencyContainer()
            ->createAuthModel()
            ->hasCurrentUser();
    }

    /**
     * @param UserTransfer $user
     *
     * @return string
     */
    public function getUserToken($user)
    {
        return $this->getDependencyContainer()
            ->createAuthModel()
            ->generateToken($user);
    }

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isIgnorable($bundle, $controller, $action)
    {
        return $this->getDependencyContainer()
            ->createAuthModel()
            ->isIgnorablePath($bundle, $controller, $action);
    }

    /**
     * @param string $hash
     *
     * @return UserTransfer
     */
    public function getSystemUser($hash)
    {
        return $this->getDependencyContainer()
            ->createAuthModel()
            ->getSystemUserByHash($hash);
    }

    /**
     * @return string
     */
    public function getCurrentUserToken()
    {
        return $this->getDependencyContainer()
            ->createAuthModel()
            ->getCurrentUserToken();
    }

}
