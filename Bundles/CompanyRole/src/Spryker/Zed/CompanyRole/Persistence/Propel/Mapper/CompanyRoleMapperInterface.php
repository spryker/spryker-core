<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRole;

interface CompanyRoleMapperInterface
{
    /**
     * Specification:
     * - Maps company role entity to transfer object.
     *
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole $companyRoleEntity
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function mapCompanyRoleEntityToTransfer(SpyCompanyRole $companyRoleEntity, CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer;

    /**
     * Specification:
     * - Maps transfer object to company role entity.
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyTransferTransfer
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole $companyRoleEntity
     *
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole
     */
    public function mapTransferToCompanyRoleEntity(CompanyRoleTransfer $companyTransferTransfer, SpyCompanyRole $companyRoleEntity): SpyCompanyRole;
}
