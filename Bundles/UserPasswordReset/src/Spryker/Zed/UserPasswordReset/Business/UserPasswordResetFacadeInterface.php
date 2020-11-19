<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserPasswordReset\Business;

interface UserPasswordResetFacadeInterface
{
    /**
     * Specification:
     * - Retrieves user by given email.
     * - Generates reset password token.
     * - Persists the reset password to DB.
     * - Executes plugins that handles user password reset request.
     * - Returns true on success.
     * - Returns false if user was not found or password reset was not saved.
     *
     * @api
     *
     * @param string $email
     *
     * @return bool
     */
    public function requestPasswordReset(string $email): bool;

    /**
     * Specification:
     * - Validates reset password token.
     * - Returns true if token is valid.
     * - Returns false if token not valid or was expired.
     * - Updates reset password status if expired.
     *
     * @api
     *
     * @param string $token
     *
     * @return bool
     */
    public function isValidPasswordResetToken(string $token): bool;

    /**
     * Specification:
     * - Sets a new user password.
     * - Updates reset password status as `used`.
     * - Returns false if reset password entity not exists.
     * - Returns true on success.
     *
     * @api
     *
     * @param string $token
     * @param string $password
     *
     * @return bool
     */
    public function setNewPassword(string $token, string $password): bool;
}
