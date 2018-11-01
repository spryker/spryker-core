<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRole;

class CompanyRoleMapper implements CompanyRoleMapperInterface
{
    /**
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole $spyCompanyRole
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function mapEntityToCompanyRoleTransfer(
        SpyCompanyRole $spyCompanyRole,
        CompanyRoleTransfer $companyRoleTransfer
    ): CompanyRoleTransfer {
        return $companyRoleTransfer->fromArray($spyCompanyRole->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole $spyCompanyRole
     *
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole
     */
    public function mapCompanyRoleTransferToEntity(
        CompanyRoleTransfer $companyRoleTransfer,
        SpyCompanyRole $spyCompanyRole
    ): SpyCompanyRole {
        $spyCompanyRole->fromArray($companyRoleTransfer->modifiedToArray());
        $spyCompanyRole->setNew($companyRoleTransfer->getIdCompanyRole() === null);
        return $spyCompanyRole;
    }
}
