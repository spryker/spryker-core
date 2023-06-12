<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Business\RequestDispatcher;

use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetDeletedTransfer;
use Generated\Shared\Transfer\AssetTransfer;
use Generated\Shared\Transfer\AssetUpdatedTransfer;

interface AssetRequestDispatcherInterface
{
 /**
  * @param \Generated\Shared\Transfer\AssetAddedTransfer $assetAddedTransfer
  *
  * @return \Generated\Shared\Transfer\AssetTransfer
  */
    public function dispatchAssetAddedTransferRequest(AssetAddedTransfer $assetAddedTransfer): AssetTransfer;

    /**
     * @param \Generated\Shared\Transfer\AssetUpdatedTransfer $assetUpdatedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function dispatchAssetUpdatedTransferRequest(AssetUpdatedTransfer $assetUpdatedTransfer): AssetTransfer;

    /**
     * @param \Generated\Shared\Transfer\AssetDeletedTransfer $assetDeletedTransfer
     *
     * @return void
     */
    public function dispatchAssetDeletedTransferRequest(AssetDeletedTransfer $assetDeletedTransfer): void;
}
