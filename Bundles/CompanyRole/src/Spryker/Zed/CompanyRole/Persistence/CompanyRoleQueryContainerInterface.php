<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence;

use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleToPermissionQuery;

interface CompanyRoleQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleToPermissionQuery
     */
    public function queryCompanyRoleToPermission(): SpyCompanyRoleToPermissionQuery;
}
