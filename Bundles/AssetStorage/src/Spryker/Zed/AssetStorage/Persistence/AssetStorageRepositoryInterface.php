<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface AssetStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $assetSlotStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationTransferCollection(
        FilterTransfer $filterTransfer,
        array $assetSlotStorageIds
    ): array;

    /**
     * @param string $assetSlot
     * @param array<string> $storeNames
     *
     * @return array<\Generated\Shared\Transfer\AssetSlotStorageTransfer>
     */
    public function findAssetStoragesByAssetSlotAndStores(string $assetSlot, array $storeNames): array;
}
