<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Business;

use Generated\Shared\Transfer\HttpRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Auth\Business\AuthBusinessFactory getFactory()
 */
class AuthFacade extends AbstractFacade implements AuthFacadeInterface
{
    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HttpRequestTransfer $httpRequestTransfer
     *
     * @return bool
     */
    public function isSystemUserRequest(HttpRequestTransfer $httpRequestTransfer): bool
    {
        return $this->getFactory()
            ->createAuthModel()
            ->isSystemUserRequest($httpRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
