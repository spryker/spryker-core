<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRole;

class CompanyRoleMapper implements CompanyRoleMapperInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole $companyRoleEntity
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function mapCompanyRoleEntityToTransfer(SpyCompanyRole $companyRoleEntity, CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer
    {
        $companyRoleTransfer->fromArray($companyRoleEntity->toArray(), true);

        return $companyRoleTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole
     */
    public function mapTransferToCompanyRoleEntity(CompanyRoleTransfer $companyRoleTransfer, SpyCompanyRole $companyRoleEntity): SpyCompanyRole
    {
        $companyRoleEntity->fromArray($companyRoleTransfer->modifiedToArray());
        $companyRoleEntity->setNew($companyRoleTransfer->getIdCompanyRole() === null);

        return $companyRoleEntity;
    }
}
