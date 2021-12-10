<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\ConditionChecker\MerchantUser;

use Generated\Shared\Transfer\UserTransfer;

interface UserRoleFilterConditionCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string $role
     *
     * @return bool
     */
    public function checkUserRoleFilterCondition(UserTransfer $userTransfer, string $role): bool;
}
