<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserExtension\Dependency\Plugin;

use Generated\Shared\Transfer\UserTransfer;

/**
 * Use this plugin to check if Symfony security authentication role should be filtered out.
 */
interface MerchantUserRoleFilterPreConditionPluginInterface
{
    /**
     * Specification:
     * - Returns true if the filtered role is not configured as Backoffice login authentication role.
     * - Returns false if the user has ACL group with Backoffice access, true otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string $role
     *
     * @return bool
     */
    public function checkCondition(UserTransfer $userTransfer, string $role): bool;
}
