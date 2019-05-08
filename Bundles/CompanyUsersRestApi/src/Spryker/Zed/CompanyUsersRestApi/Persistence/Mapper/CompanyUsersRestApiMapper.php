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

class CompanyUsersRestApiMapper
{
    /**
     * @param array $companyUsers
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole[] $companyRoles
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function mapCompanyUserCollection(array $companyUsers, array $companyRoles): CompanyUserCollectionTransfer
    {
        $companyUserCollectionTransfer = new CompanyUserCollectionTransfer();
        $indexedCompanyRoles = $this->indexCompanyRoleEntities($companyRoles);
        foreach ($companyUsers as $companyUser) {
            $companyUserTransfer = $this->mapEntityTransferToCompanyUserTransfer($companyUser);
            $companyUserTransfer->setCompanyRoleCollection(
                $this->mapCompanyRoleEntitiesToCompanyRoleCollectionTransfer($indexedCompanyRoles[$companyUserTransfer->getIdCompanyUser()] ?? [])
            );
            $companyUserCollectionTransfer
                ->addCompanyUser($companyUserTransfer);
        }

        return $companyUserCollectionTransfer;
    }

    /**
     * @param array $companyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function mapEntityTransferToCompanyUserTransfer(array $companyUser): CompanyUserTransfer
    {
         return (new CompanyUserTransfer())->fromArray($companyUser, true);
    }

    /**
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole[] $companyRoles
     *
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole[][]
     */
    protected function indexCompanyRoleEntities(array $companyRoles): array
    {
        $indexedCompanyRoles = [];
        foreach ($companyRoles as $companyRole) {
            foreach ($companyRole->getSpyCompanyRoleToCompanyUsers() as $companyRoleToCompanyUser) {
                $indexedCompanyRoles[$companyRoleToCompanyUser->getFkCompanyUser()][] = $companyRole;
            }
        }

        return $indexedCompanyRoles;
    }

    /**
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole[] $companyRoles
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    protected function mapCompanyRoleEntitiesToCompanyRoleCollectionTransfer(array $companyRoles): CompanyRoleCollectionTransfer
    {
        $companyRoleCollectionTransfer = new CompanyRoleCollectionTransfer();
        foreach ($companyRoles as $companyRole) {
            $companyRoleCollectionTransfer->addRole(
                (new CompanyRoleTransfer())->fromArray($companyRole->toArray(), true)
            );
        }

        return $companyRoleCollectionTransfer;
    }
}
