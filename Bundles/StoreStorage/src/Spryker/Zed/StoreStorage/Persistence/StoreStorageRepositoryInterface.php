<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Persistence;

use Generated\Shared\Transfer\StoreStorageCriteriaTransfer;

interface StoreStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreStorageCriteriaTransfer $storeStorageCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getStoreStorageSynchronizationDataTransfers(StoreStorageCriteriaTransfer $storeStorageCriteriaTransfer): array;
}
