<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Auth\Business\AuthBusinessFactory getFactory()
 */
class AuthFacade extends AbstractFacade implements AuthFacadeInterface
{
    /**
     * @api
     *
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
     * @api
     *
     * @return void
     */
    public function logout()
    {
        $this->getFactory()
            ->createAuthModel()
            ->logout();
    }

    /**
     * @api
     *
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
     * @api
     *
     * @return bool
     */
    public function hasCurrentUser()
    {
        return $this->getFactory()
            ->createAuthModel()
            ->hasCurrentUser();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $user
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
     * @api
     *
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
     * @api
     *
     * @param string $hash
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getSystemUser($hash)
    {
        return $this->getFactory()
            ->createAuthModel()
            ->getSystemUserByHash($hash);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCurrentUserToken()
    {
        return $this->getFactory()
            ->createAuthModel()
            ->getCurrentUserToken();
    }

    /**
     * @api
     *
     * @param string $email
     *
     * @return bool
     */
    public function requestPasswordReset($email)
    {
        return $this->getFactory()->createPasswordReset()->requestToken($email);
    }

    /**
     * @api
     *
     * @param string $token
     *
     * @return bool
     */
    public function isValidPasswordResetToken($token)
    {
        return $this->getFactory()->createPasswordReset()->isValidToken($token);
    }

    /**
     * @api
     *
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
