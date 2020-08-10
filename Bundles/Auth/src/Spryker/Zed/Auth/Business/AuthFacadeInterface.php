<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Business;

use Generated\Shared\Transfer\HttpRequestTransfer;

interface AuthFacadeInterface
{
    /**
     * @api
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function login($username, $password);

    /**
     * @api
     *
     * @return void
     */
    public function logout();

    /**
     * @api
     *
     * @param string $token
     *
     * @return bool
     */
    public function isAuthenticated($token);

    /**
     * @api
     *
     * @return bool
     */
    public function hasCurrentUser();

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return string
     */
    public function getUserToken($user);

    /**
     * @api
     *
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isIgnorable($bundle, $controller, $action);

    /**
     * @api
     *
     * @param string $hash
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getSystemUser($hash);

    /**
     * @api
     *
     * @return string
     */
    public function getCurrentUserToken();

    /**
     * Specification:
     *  - Returns true if auth token exists in `HttpRequestTransfer.headers` and it belongs to the system user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HttpRequestTransfer $httpRequestTransfer
     *
     * @return bool
     */
    public function isSystemUserRequest(HttpRequestTransfer $httpRequestTransfer): bool;

    /**
     * @api
     *
     * @param string $email
     *
     * @return bool
     */
    public function requestPasswordReset($email);

    /**
     * @api
     *
     * @param string $token
     *
     * @return bool
     */
    public function isValidPasswordResetToken($token);

    /**
     * @api
     *
     * @param string $token
     * @param string $password
     *
     * @return bool
     */
    public function resetPassword($token, $password);
}
