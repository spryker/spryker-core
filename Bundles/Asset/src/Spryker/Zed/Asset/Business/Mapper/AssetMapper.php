<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Business\Mapper;

use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetDeletedTransfer;
use Generated\Shared\Transfer\AssetTransfer;

class AssetMapper implements AssetMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\AssetAddedTransfer $assetAddedTransfer
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function mapAssetAddedTransferToAssetTransfer(
        AssetAddedTransfer $assetAddedTransfer,
        AssetTransfer $assetTransfer
    ): AssetTransfer {
        return $assetTransfer
            ->setAssetUuid($assetAddedTransfer->getAssetIdentifier())
            ->setAssetContent($assetAddedTransfer->getAssetView())
            ->setAssetName($assetAddedTransfer->getAssetName())
            ->setAssetSlot($assetAddedTransfer->getAssetSlot())
            ->setIsActive(true)
            ->setLastMessageTimestamp($assetAddedTransfer->getMessageAttributesOrFail()->getTimestamp());
    }

    /**
     * @param \Generated\Shared\Transfer\AssetDeletedTransfer $assetDeletedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function generateAssetTransferFromAssetDeletedTransfer(AssetDeletedTransfer $assetDeletedTransfer): AssetTransfer
    {
        $assetTransfer = new AssetTransfer();
        $assetTransfer->setAssetUuid($assetDeletedTransfer->getAssetIdentifier());
        $assetTransfer->setAssetName($assetDeletedTransfer->getAssetNameOrFail());
        $assetTransfer->setAssetContent($assetDeletedTransfer->getAssetViewOrFail());
        $assetTransfer->setAssetSlot($assetDeletedTransfer->getAssetSlotOrFail());
        $assetTransfer->setIsActive(false);
        $assetTransfer->setLastMessageTimestamp($assetDeletedTransfer->getMessageAttributesOrFail()->getTimestamp());

        return $assetTransfer;
    }
}
