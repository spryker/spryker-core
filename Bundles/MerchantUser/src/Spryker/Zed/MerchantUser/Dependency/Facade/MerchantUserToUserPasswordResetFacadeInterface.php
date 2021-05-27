<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Dependency\Facade;

use Generated\Shared\Transfer\UserPasswordResetRequestTransfer;

interface MerchantUserToUserPasswordResetFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer
     *
     * @return bool
     */
    public function requestPasswordReset(UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer): bool;

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
