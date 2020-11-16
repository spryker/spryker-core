<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserPasswordReset\Business\ResetPassword;

interface ResetPasswordInterface
{
    /**
     * @param string $email
     *
     * @return bool
     */
    public function requestPasswordReset(string $email): bool;

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isValidPasswordResetToken(string $token): bool;

    /**
     * @param string $token
     * @param string $password
     *
     * @return bool
     */
    public function setNewPassword(string $token, string $password): bool;
}
