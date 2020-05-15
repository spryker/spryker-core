<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage;
use Propel\Runtime\Collection\ObjectCollection;

class CompanyUserStorageMapper implements CompanyUserStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     * @param \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage $spyCompanyUserEntityTransfer
     *
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage
     */
    public function mapCompanyUserStorageTransferToCompanyUserStorageEntity(
        CompanyUserStorageTransfer $companyUserStorageTransfer,
        SpyCompanyUserStorage $spyCompanyUserEntityTransfer
    ): SpyCompanyUserStorage {
        $spyCompanyUserEntityTransfer->setFkCompanyUser($companyUserStorageTransfer->getIdCompanyUser());
        $spyCompanyUserEntityTransfer->setData($companyUserStorageTransfer->toArray());

        return $spyCompanyUserEntityTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[] $companyUserStorageEntityCollection
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapCompanyUserStorageEntityCollectionToSynchronizationDataTransfers(ObjectCollection $companyUserStorageEntityCollection): array
    {
        $synchronizationDataTransfers = [];

        foreach ($companyUserStorageEntityCollection as $companyUserStorageEntity) {
            $synchronizationDataTransfers[] = (new SynchronizationDataTransfer())->fromArray($companyUserStorageEntity->toArray(), true);
        }

        return $synchronizationDataTransfers;
    }
}
