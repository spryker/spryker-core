<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\UserTransfer;

/**
 * Use this plugin to restrict login to the backoffice.
 */
interface UserLoginRestrictionPluginInterface
{
    /**
     * Specification:
     * - Checks if the user is restricted.
     * - Runs after user data is loaded from the data source.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    public function isRestricted(UserTransfer $userTransfer): bool;
}
