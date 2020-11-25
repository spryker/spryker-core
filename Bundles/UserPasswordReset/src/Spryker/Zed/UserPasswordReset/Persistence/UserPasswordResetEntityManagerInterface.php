<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserPasswordReset\Persistence;

use Generated\Shared\Transfer\ResetPasswordTransfer;

interface UserPasswordResetEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ResetPasswordTransfer $resetPasswordTransfer
     *
     * @return \Generated\Shared\Transfer\ResetPasswordTransfer
     */
    public function createResetPassword(ResetPasswordTransfer $resetPasswordTransfer): ResetPasswordTransfer;

    /**
     * @param \Generated\Shared\Transfer\ResetPasswordTransfer $resetPasswordTransfer
     *
     * @return \Generated\Shared\Transfer\ResetPasswordTransfer
     */
    public function updateResetPassword(ResetPasswordTransfer $resetPasswordTransfer): ResetPasswordTransfer;
}
