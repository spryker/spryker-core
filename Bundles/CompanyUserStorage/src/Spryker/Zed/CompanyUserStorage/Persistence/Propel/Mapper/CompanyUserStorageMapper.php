<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUser;
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
            $synchronizationDataTransfers[] = $this->mapCompanyUserStorageEntityToSynchronizationDataTransfer(
                $companyUserStorageEntity,
                new SynchronizationDataTransfer()
            );
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage $companyUserStorageEntity
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $synchronizationDataTransfer
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    public function mapCompanyUserStorageEntityToSynchronizationDataTransfer(
        SpyCompanyUserStorage $companyUserStorageEntity,
        SynchronizationDataTransfer $synchronizationDataTransfer
    ): SynchronizationDataTransfer {
        /** @var string $data */
        $data = $companyUserStorageEntity->getData();

        return $synchronizationDataTransfer
            ->setData($data)
            ->setKey($companyUserStorageEntity->getKey());
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\CompanyUser\Persistence\SpyCompanyUser[] $companyUserEntityCollection
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    public function mapCompanyUserEntityCollectionToCompanyUserTransfers(ObjectCollection $companyUserEntityCollection): array
    {
        $companyUserTransfers = [];
        foreach ($companyUserEntityCollection as $companyUserEntity) {
            $companyUserTransfers[] = $this->mapCompanyUserEntityToCompanyUserTransfer(
                $companyUserEntity,
                new CompanyUserTransfer()
            );
        }

        return $companyUserTransfers;
    }

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUser $companyUserEntity
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function mapCompanyUserEntityToCompanyUserTransfer(
        SpyCompanyUser $companyUserEntity,
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserTransfer {
        return $companyUserTransfer->fromArray($companyUserEntity->toArray(), true);
    }
}
