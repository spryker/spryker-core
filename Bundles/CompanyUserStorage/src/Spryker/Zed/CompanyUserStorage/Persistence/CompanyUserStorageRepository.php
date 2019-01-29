<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Persistence;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStoragePersistenceFactory getFactory()
 */
class CompanyUserStorageRepository extends AbstractRepository implements CompanyUserStorageRepositoryInterface
{
    /**
     * @param array $companyUserIds
     *
     * @return array
     */
    public function findCompanyUserStorageTransfers(array $companyUserIds): array
    {
        if (!$companyUserIds) {
            return [];
        }

        $query = $this->getFactory()
            ->createCompanyUserStorageQuery()
            ->filterByFkCompanyUser_In($companyUserIds);
        $companyUserStorageEntityCollection = $query->find();

        $companyUserStorageTransfers = [];
        $mapper = $this->getFactory()->createCompanyUserStorageMapper();
        foreach ($companyUserStorageEntityCollection as $companyUserStorageEntity) {
            $companyUserStorageTransfers[] = $mapper->mapCompanyUserStorageEntityToCompanyUserStorageTransfer(
                $companyUserStorageEntity,
                new CompanyUserStorageTransfer()
            );
        }

        return $companyUserStorageTransfers;
    }

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

        return $query->find()->getArrayCopy('fkCompanyUser');
    }

    /**
     * @return \Generated\Shared\Transfer\SpyCompanyUserStorageEntityTransfer[]
     */
    public function findAllCompanyUserStorageEntities(): array
    {
        $query = $this->getFactory()
            ->createCompanyUserStorageQuery();

        return $query->find()->getArrayCopy();
    }
}
