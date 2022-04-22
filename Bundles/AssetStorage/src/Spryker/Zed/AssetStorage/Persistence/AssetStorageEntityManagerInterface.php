<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Persistence;

use Generated\Shared\Transfer\AssetTransfer;

interface AssetStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     * @param string $storeName
     * @param array $assetSlotStorageToDelete
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function createAssetStorage(
        AssetTransfer $assetTransfer,
        string $storeName,
        array $assetSlotStorageToDelete
    ): void;

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     * @param array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer> $assetSlotStorageToUpdate
     * @param array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer> $assetSlotStorageToDelete
     *
     * @return void
     */
    public function updateAssetStorage(
        AssetTransfer $assetTransfer,
        array $assetSlotStorageToUpdate,
        array $assetSlotStorageToDelete
    ): void;

    /**
     * @param array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer> $assetSlotsStorageEntityTransfers
     * @param int $idAsset
     *
     * @return void
     */
    public function removeAssetStorageByIdAsset(array $assetSlotsStorageEntityTransfers, int $idAsset): void;
}
