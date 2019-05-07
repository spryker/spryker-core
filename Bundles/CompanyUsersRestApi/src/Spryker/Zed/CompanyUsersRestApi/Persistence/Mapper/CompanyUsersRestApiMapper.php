<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SpyCompanyRoleToPermissionEntityTransfer;
use Generated\Shared\Transfer\SpyCompanyUserEntityTransfer;

class CompanyUsersRestApiMapper
{
    /**
     * @param array $companyUsers
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function mapCompanyUserCollection(array $companyUsers): CompanyUserCollectionTransfer
    {
        $companyUserCollectionTransfer = new CompanyUserCollectionTransfer();
        foreach ($companyUsers as $companyUser) {
            $companyUserCollectionTransfer
                ->addCompanyUser($this->mapEntityTransferToCompanyUserTransfer($companyUser));
        }

        return $companyUserCollectionTransfer;
    }

    /**
     * @param array $companyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function mapEntityTransferToCompanyUserTransfer(
        array $companyUser
    ): CompanyUserTransfer {
        $companyUserTransfer = (new CompanyUserTransfer())->fromArray($companyUser, true);
        $companyUserTransfer->setCompanyRoleCollection(new CompanyRoleCollectionTransfer());
        foreach ($companyUser[ucfirst(SpyCompanyUserEntityTransfer::SPY_COMPANY_ROLE_TO_COMPANY_USERS)] as $companyRoleToCompanyUser) {
            $companyRoleTransfer = (new CompanyRoleTransfer())
                ->fromArray($companyRoleToCompanyUser[ucfirst(SpyCompanyRoleToPermissionEntityTransfer::COMPANY_ROLE)], true);

            $companyUserTransfer->getCompanyRoleCollection()->addRole($companyRoleTransfer);
        }

        return $companyUserTransfer;
    }
}
