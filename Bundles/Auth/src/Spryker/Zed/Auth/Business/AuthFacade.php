<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\UserTransfer;

/**
 * @method AuthBusinessFactory getFactory()
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
        return $this->getFactory()
            ->createAuthModel()
            ->authenticate($username, $password);
    }

    /**
     * @return void
     */
    public function logout()
    {
        $this->getFactory()
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
        return $this->getFactory()
            ->createAuthModel()
            ->isAuthorized($token);
    }

    /**
     * @return bool
     */
    public function hasCurrentUser()
    {
        return $this->getFactory()
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
        return $this->getFactory()
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
        return $this->getFactory()
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
        return $this->getFactory()
            ->createAuthModel()
            ->getSystemUserByHash($hash);
    }

    /**
     * @return string
     */
    public function getCurrentUserToken()
    {
        return $this->getFactory()
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
        return $this->getFactory()->createPasswordReset()->requestToken($email);
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isValidPasswordResetToken($token)
    {
        return $this->getFactory()->createPasswordReset()->isValidToken($token);
    }

    /**
     * @param string $token
     * @param string $password
     *
     * @return bool
     */
    public function resetPassword($token, $password)
    {
        return $this->getFactory()->createPasswordReset()->resetPassword($token, $password);
    }

}
