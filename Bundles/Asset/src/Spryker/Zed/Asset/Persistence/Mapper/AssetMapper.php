<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Persistence\Mapper;

use Generated\Shared\Transfer\AssetTransfer;
use Orm\Zed\Asset\Persistence\SpyAsset;

class AssetMapper
{
    /**
     * @param \Orm\Zed\Asset\Persistence\SpyAsset $assetEntity
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function mapAssetEntityToAssetTransfer(
        SpyAsset $assetEntity,
        AssetTransfer $assetTransfer
    ): AssetTransfer {
        $assetTransfer->fromArray($assetEntity->toArray(), true);

        $assetEntity->initSpyAssetStores(false);

        if ($assetEntity->getSpyAssetStores()->count() > 0) {
            foreach ($assetEntity->getSpyAssetStores() as $assetStore) {
                $assetTransfer->addStore($assetStore->getSpyStore()->getName());
            }
        }

        return $assetTransfer;
    }
}
