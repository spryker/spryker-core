<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Communication\CompanyUserStorageMapper;

interface CompanyUserStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryImageStorageTransfer[] $categoryImageStorageTransfers
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapCompanyUserStorageTransfersCollectionToSynchronizationDataTransferCollection(array $categoryImageStorageTransfers): array;
}
