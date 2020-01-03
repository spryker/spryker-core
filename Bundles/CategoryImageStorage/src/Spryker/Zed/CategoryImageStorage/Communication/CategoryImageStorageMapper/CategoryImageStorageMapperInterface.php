<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Communication\CategoryImageStorageMapper;

interface CategoryImageStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryImageStorageTransfer[] $categoryImageStorageTransfers
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapCategoryImageStorageTransferCollectionToSynchronizationDataTransferCollection(array $categoryImageStorageTransfers): array;
}
