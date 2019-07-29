<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Persistence;

use Generated\Shared\Transfer\SpyCompanyUserStorageEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStoragePersistenceFactory getFactory()
 */
class CompanyUserStorageRepository extends AbstractRepository implements CompanyUserStorageRepositoryInterface
{
    /**
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
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[]
     */
    public function findAllCompanyUserStorageEntities(): array
    {
        return $this->getFactory()
            ->createCompanyUserStorageQuery()
            ->find()
            ->getArrayCopy();
    }
}
