<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserPasswordResetExtension\Dependency\Plugin;

use Generated\Shared\Transfer\UserPasswordResetRequestTransfer;

/**
 * Provides functionality to handle user password reset request.
 */
interface UserPasswordResetRequestStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if strategy is applicable for a password reset request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer): bool;

    /**
     * Specification:
     *  - Handles user password reset request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer
     *
     * @return void
     */
    public function handleUserPasswordResetRequest(UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer): void;
}
