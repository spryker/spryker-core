<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\UserRoleFilter;

use Generated\Shared\Transfer\UserTransfer;

interface BackofficeMerchantUserRoleFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param array<string> $roles
     *
     * @return array<string>
     */
    public function filterUserRoles(UserTransfer $userTransfer, array $roles): array;
}
