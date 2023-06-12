<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Persistence;

use Generated\Shared\Transfer\AssetTransfer;

interface AssetEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function saveAssetWithStores(
        AssetTransfer $assetTransfer,
        array $storeTransfers
    ): AssetTransfer;

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function saveAsset(AssetTransfer $assetTransfer): AssetTransfer;

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return void
     */
    public function deleteAsset(AssetTransfer $assetTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return void
     */
    public function deleteAssetStores(AssetTransfer $assetTransfer, array $storeTransfers): void;

    /**
     * @return bool
     */
    public function hasIsActiveColumn(): bool;
}
