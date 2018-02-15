<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRole;

interface CompanyRolePermissionMapperInterface
{
    /**
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole $companyRole
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function hydratePermissionCollection(SpyCompanyRole $companyRole, CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer;
}
