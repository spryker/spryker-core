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
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUser;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Map\TableMap;

class CompanyUsersRestApiMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $companyUserCollection
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function mapCompanyUserEntitiesToCompanyUserCollectionTransfer(
        ObjectCollection $companyUserCollection,
        CompanyUserCollectionTransfer $companyUserCollectionTransfer
    ): CompanyUserCollectionTransfer {
        foreach ($companyUserCollection as $companyUserEntity) {
            $companyUserTransfer = $this->mapCompanyUserEntityToCompanyUserTransfer(
                $companyUserEntity,
                new CompanyUserTransfer()
            );

            $companyUserCollectionTransfer->addCompanyUser($companyUserTransfer);
        }

        return $companyUserCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $companyRoleCollection
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function mapCompanyRoleCollectionTransferToCompanyUserCollection(
        ObjectCollection $companyRoleCollection,
        CompanyUserCollectionTransfer $companyUserCollectionTransfer
    ): CompanyUserCollectionTransfer {
        $indexedCompanyRoles = $this->indexCompanyRoleEntities($companyRoleCollection);
        foreach ($companyUserCollectionTransfer->getCompanyUsers() as $companyUserTransfer) {
            if (empty($indexedCompanyRoles[$companyUserTransfer->getIdCompanyUser()])) {
                continue;
            }

            $companyRoleCollectionTransfer = $this->mapCompanyRoleEntitiesToCompanyRoleCollectionTransfer(
                $indexedCompanyRoles[$companyUserTransfer->getIdCompanyUser()],
                new CompanyRoleCollectionTransfer()
            );

            $companyUserTransfer->setCompanyRoleCollection($companyRoleCollectionTransfer);
        }

        return $companyUserCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUser $companyUserEntity
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function mapCompanyUserEntityToCompanyUserTransfer(
        SpyCompanyUser $companyUserEntity,
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserTransfer {
        $companyUserData = $companyUserEntity->toArray(
            TableMap::TYPE_PHPNAME,
            true,
            [],
            true
        );

        return $companyUserTransfer->fromArray($companyUserData, true);
    }

    /**
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole[] $companyRoleEntities
     * @param \Generated\Shared\Transfer\CompanyRoleCollectionTransfer $companyRoleCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    protected function mapCompanyRoleEntitiesToCompanyRoleCollectionTransfer(
        array $companyRoleEntities,
        CompanyRoleCollectionTransfer $companyRoleCollectionTransfer
    ): CompanyRoleCollectionTransfer {
        foreach ($companyRoleEntities as $companyRoleEntity) {
            $companyRoleCollectionTransfer->addRole(
                (new CompanyRoleTransfer())->fromArray($companyRoleEntity->toArray(), true)
            );
        }

        return $companyRoleCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $companyRoles
     *
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole[][]
     */
    protected function indexCompanyRoleEntities(ObjectCollection $companyRoles): array
    {
        $indexedCompanyRoles = [];
        foreach ($companyRoles as $companyRoleEntity) {
            foreach ($companyRoleEntity->getSpyCompanyRoleToCompanyUsers() as $companyRoleToCompanyUserEntity) {
                $indexedCompanyRoles[$companyRoleToCompanyUserEntity->getFkCompanyUser()][] = $companyRoleEntity;
            }
        }

        return $indexedCompanyRoles;
    }
}
