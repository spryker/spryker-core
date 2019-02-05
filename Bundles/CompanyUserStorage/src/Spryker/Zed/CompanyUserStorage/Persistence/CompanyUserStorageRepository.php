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

        $query = $this->getFactory()
            ->createCompanyUserStorageQuery()
            ->filterByFkCompanyUser_In($companyUserIds);

        // indexing resulting array with corresponding companyUserIds
        return $query->find()
            ->getArrayCopy(SpyCompanyUserStorageEntityTransfer::FK_COMPANY_USER);
    }

    /**
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[]
     */
    public function findAllCompanyUserStorageEntities(): array
    {
        $query = $this->getFactory()
            ->createCompanyUserStorageQuery();

        return $query->find()->getArrayCopy();
    }
}
