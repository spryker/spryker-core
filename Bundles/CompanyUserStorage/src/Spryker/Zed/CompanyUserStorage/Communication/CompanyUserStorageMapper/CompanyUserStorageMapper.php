<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Communication\CompanyUserStorageMapper;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;

class CompanyUserStorageMapper implements CompanyUserStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer[] $companyUserStorageTransfers
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapCompanyUserStorageTransfersCollectionToSynchronizationDataTransferCollection(array $companyUserStorageTransfers): array
    {
        $synchronizationDataTransfers = [];

        foreach ($companyUserStorageTransfers as $companyUserStorageTransfer) {
            $synchronizationDataTransfers[] = $this->mapCompanyUserStorageTransferToSynchronizationDataTransfer(
                $companyUserStorageTransfer,
                new SynchronizationDataTransfer()
            );
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $synchronizationDataTransfer
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    protected function mapCompanyUserStorageTransferToSynchronizationDataTransfer(
        CompanyUserStorageTransfer $companyUserStorageTransfer,
        SynchronizationDataTransfer $synchronizationDataTransfer
    ): SynchronizationDataTransfer {
        return $synchronizationDataTransfer
            ->setData($companyUserStorageTransfer->getData())
            ->setKey($companyUserStorageTransfer->getKey());
    }
}
