<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\SpyCompanyUserStorageEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStoragePersistenceFactory getFactory()
 */
class CompanyUserStorageRepository extends AbstractRepository implements CompanyUserStorageRepositoryInterface
{
    /**
     * @deprecated Use getCompanyUserStorageByFilter instead.
     *
     * @param array $companyUserIds
     *
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[]
     */
    public function findCompanyUserStorageEntities(array $companyUserIds): array
    {
        if (!$companyUserIds) {
            return [];
        }

        return $this->getFactory()
            ->createCompanyUserStorageQuery()
            ->filterByFkCompanyUser_In($companyUserIds)
            ->find()
            ->getArrayCopy(SpyCompanyUserStorageEntityTransfer::FK_COMPANY_USER); // indexing resulting array with corresponding companyUserIds
    }

    /**
     * @deprecated Use getAllCompanyUserStorageByFilter instead.
     *
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[]
     */
    public function findAllCompanyUserStorageEntities(): array
    {
        return $this->getFactory()
            ->createCompanyUserStorageQuery()
            ->find()
            ->getArrayCopy();
    }

    /**
     * @module CompanyUserStorage
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[]
     */
    public function getAllCompanyUserStorageByFilter(FilterTransfer $filterTransfer): array
    {
        return $this->getFactory()
            ->createCompanyUserStorageQuery()
            ->limit($filterTransfer->getLimit())
            ->offset($filterTransfer->getOffset())
            ->find()
            ->getArrayCopy();
    }

    /**
     * @module CompanyUserStorage
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array $companyUserIds
     *
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[]
     */
    public function getCompanyUserStorageByFilter(FilterTransfer $filterTransfer, array $companyUserIds): array
    {
        if (!$companyUserIds) {
            return [];
        }

        return $this->getFactory()
            ->createCompanyUserStorageQuery()
            ->filterByFkCompanyUser_In($companyUserIds)
            ->limit($filterTransfer->getLimit())
            ->offset($filterTransfer->getOffset())
            ->find()
            ->getArrayCopy(SpyCompanyUserStorageEntityTransfer::FK_COMPANY_USER); // indexing resulting array with corresponding companyUserIds
    }
}
