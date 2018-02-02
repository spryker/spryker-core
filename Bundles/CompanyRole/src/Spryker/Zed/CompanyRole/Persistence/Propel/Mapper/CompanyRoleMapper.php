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
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole $companyEntity
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function mapCompanyRoleEntityToTransfer(SpyCompanyRole $companyEntity): CompanyRoleTransfer
    {
        $companyRole = $this->createCompanyRoleTransfer();
        $companyRole->fromArray($companyEntity->toArray());

        return $companyRole;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRole
     *
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole
     */
    public function mapTransferToCompanyRoleEntity(CompanyRoleTransfer $companyRole): SpyCompanyRole
    {
        $companyEntity = $this->createCompanyRoleEntity();
        $companyEntity->fromArray($companyRole->modifiedToArray());

        return $companyEntity;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    protected function createCompanyRoleTransfer(): CompanyRoleTransfer
    {
        return new CompanyRoleTransfer();
    }

    /**
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole
     */
    protected function createCompanyRoleEntity(): SpyCompanyRole
    {
        return new SpyCompanyRole();
    }
}
