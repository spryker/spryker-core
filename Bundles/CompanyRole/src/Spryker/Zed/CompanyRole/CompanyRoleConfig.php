<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CompanyRoleConfig extends AbstractBundleConfig
{
    protected const DEFAULT_ADMIN_ROLE_NAME = 'Administrator';

    /**
     * @return string
     */
    public function getDefaultAdminRoleName(): string
    {
        return static::DEFAULT_ADMIN_ROLE_NAME;
    }

    /**
     * @return string[]
     */
    public function getAdminRolePermissions(): array
    {
        return [];
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer[]
     */
    public function getCompanyRoles(): array
    {
        return [];
    }
}
