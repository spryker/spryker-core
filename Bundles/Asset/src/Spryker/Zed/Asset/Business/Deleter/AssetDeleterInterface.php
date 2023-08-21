<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Business\Deleter;

use Generated\Shared\Transfer\AssetDeletedTransfer;

interface AssetDeleterInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\Asset\Business\Deleter\AssetDeleterInterface::removeAsset()} instead.
     *
     * @param \Generated\Shared\Transfer\AssetDeletedTransfer $assetDeletedTransfer
     *
     * @return void
     */
    public function deleteAsset(AssetDeletedTransfer $assetDeletedTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\AssetDeletedTransfer $assetDeletedTransfer
     *
     * @return void
     */
    public function removeAsset(AssetDeletedTransfer $assetDeletedTransfer): void;
}
