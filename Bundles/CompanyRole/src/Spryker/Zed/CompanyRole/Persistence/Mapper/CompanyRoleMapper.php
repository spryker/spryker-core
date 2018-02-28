<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\SpyCompanyRoleEntityTransfer;

class CompanyRoleMapper implements CompanyRoleMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyCompanyRoleEntityTransfer $companyRoleEntityTransfer
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function mapEntityTransferToCompanyRoleTransfer(
        SpyCompanyRoleEntityTransfer $companyRoleEntityTransfer,
        CompanyRoleTransfer $companyRoleTransfer
    ): CompanyRoleTransfer {
        return $companyRoleTransfer->fromArray($companyRoleEntityTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     * @param \Generated\Shared\Transfer\SpyCompanyRoleEntityTransfer $companyRoleEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCompanyRoleEntityTransfer
     */
    public function mapCompanyRoleTransferToEntityTransfer(
        CompanyRoleTransfer $companyRoleTransfer,
        SpyCompanyRoleEntityTransfer $companyRoleEntityTransfer
    ): SpyCompanyRoleEntityTransfer {
        return $companyRoleEntityTransfer->fromArray($companyRoleTransfer->modifiedToArray(), true);
    }
}
