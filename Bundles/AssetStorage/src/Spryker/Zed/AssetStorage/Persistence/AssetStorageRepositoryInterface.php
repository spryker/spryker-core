<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Persistence;

interface AssetStorageRepositoryInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer>
     */
    public function findAssetStorages(): array;

    /**
     * @param array<int> $ids
     *
     * @return array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer>
     */
    public function findAssetStoragesByAssetIds(array $ids): array;

    /**
     * @param string $assetSlot
     *
     * @return array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer>
     */
    public function findAssetStoragesByAssetSlot(string $assetSlot): array;

    /**
     * @param string $assetSlot
     * @param array<string> $storeNames
     *
     * @return array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer>
     */
    public function findAssetStoragesWithAssetSlotNotEqualAndByStores(string $assetSlot, array $storeNames): array;

    /**
     * @param string $assetSlot
     * @param array<string> $storeNames
     *
     * @return array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer>
     */
    public function findAssetStoragesByAssetSlotAndStores(string $assetSlot, array $storeNames): array;
}
