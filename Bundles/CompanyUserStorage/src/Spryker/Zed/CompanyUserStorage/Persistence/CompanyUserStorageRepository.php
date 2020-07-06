<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\SpyCompanyUserStorageEntityTransfer;
use Propel\Runtime\Formatter\ObjectFormatter;
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
     * @deprecated Use {@link getSynchronizationDataTransfersByFilterAndCompanyUserIds()} instead.
     *
     * @see \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepositoryInterface::getSynchronizationDataTransfersByFilterAndCompanyUserIds()
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
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $companyUserIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationDataTransfersByFilterAndCompanyUserIds(FilterTransfer $filterTransfer, array $companyUserIds = []): array
    {
        $query = $this->getFactory()
            ->createCompanyUserStorageQuery();

        if ($companyUserIds !== []) {
            $query->filterByFkCompanyUser_In($companyUserIds);
        }

        $companyUserStorageEntities = $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(ObjectFormatter::class)
            ->find();

        return $this->getFactory()
            ->createCompanyUserStorageMapper()
            ->mapCompanyUserStorageEntityCollectionToSynchronizationDataTransfers($companyUserStorageEntities);
    }
}
