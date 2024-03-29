<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Business\Creator;

use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetTransfer;

interface AssetCreatorInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\Asset\Business\Creator\AssetCreatorInterface::createAsset()} instead.
     *
     * @param \Generated\Shared\Transfer\AssetAddedTransfer $assetAddedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function addAsset(AssetAddedTransfer $assetAddedTransfer): AssetTransfer;

    /**
     * @param \Generated\Shared\Transfer\AssetAddedTransfer $assetAddedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function createAsset(AssetAddedTransfer $assetAddedTransfer): AssetTransfer;
}
