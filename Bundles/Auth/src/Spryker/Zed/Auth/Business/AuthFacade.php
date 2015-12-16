<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\UserTransfer;

/**
 * @method AuthBusinessFactory getBusinessFactory()
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
        return $this->getBusinessFactory()
            ->createAuthModel()
            ->authenticate($username, $password);
    }

    /**
     * @return void
     */
    public function logout()
    {
        $this->getBusinessFactory()
            ->createAuthModel()
            ->logout();
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isAuthenticated($token)
    {
        return $this->getBusinessFactory()
            ->createAuthModel()
            ->isAuthorized($token);
    }

    /**
     * @return bool
     */
    public function hasCurrentUser()
    {
        return $this->getBusinessFactory()
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
        return $this->getBusinessFactory()
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
        return $this->getBusinessFactory()
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
        return $this->getBusinessFactory()
            ->createAuthModel()
            ->getSystemUserByHash($hash);
    }

    /**
     * @return string
     */
    public function getCurrentUserToken()
    {
        return $this->getBusinessFactory()
            ->createAuthModel()
            ->getCurrentUserToken();
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function requestPasswordReset($email)
    {
        return $this->getBusinessFactory()->createPasswordReset()->requestToken($email);
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isValidPasswordResetToken($token)
    {
        return $this->getBusinessFactory()->createPasswordReset()->isValidToken($token);
    }

    /**
     * @param string $token
     * @param string $password
     *
     * @return bool
     */
    public function resetPassword($token, $password)
    {
        return $this->getBusinessFactory()->createPasswordReset()->resetPassword($token, $password);
    }

}
