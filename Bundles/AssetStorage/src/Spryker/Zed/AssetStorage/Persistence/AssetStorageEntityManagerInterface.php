<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Persistence;

use Generated\Shared\Transfer\AssetSlotStorageTransfer;

interface AssetStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AssetSlotStorageTransfer $assetSlotStorageTransfer
     *
     * @return void
     */
    public function saveAssetSlotStorage(AssetSlotStorageTransfer $assetSlotStorageTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\AssetSlotStorageTransfer $assetSlotStorageTransfer
     *
     * @return void
     */
    public function deleteAssetSlotStorage(AssetSlotStorageTransfer $assetSlotStorageTransfer): void;
}
