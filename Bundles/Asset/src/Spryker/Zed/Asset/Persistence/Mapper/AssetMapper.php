<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Persistence\Mapper;

use Generated\Shared\Transfer\AssetCollectionTransfer;
use Generated\Shared\Transfer\AssetTransfer;
use Orm\Zed\Asset\Persistence\SpyAsset;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Asset\Business\TimeStamp\AssetTimeStamp;

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

        if (method_exists($assetEntity, 'getLastMessageTimestamp')) {
            $timestamp = $assetEntity->getLastMessageTimestamp(AssetTimeStamp::TIMESTAMP_FORMAT);
            $assetTransfer->setLastMessageTimestamp($timestamp);
        }

        $assetEntity->initSpyAssetStores(false);

        if ($assetEntity->getSpyAssetStores()->count() > 0) {
            foreach ($assetEntity->getSpyAssetStores() as $assetStore) {
                $assetTransfer->addStore($assetStore->getSpyStore()->getName());
            }
        }

        return $assetTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Asset\Persistence\SpyAsset> $assetEntityCollection
     * @param \Generated\Shared\Transfer\AssetCollectionTransfer $assetCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\AssetCollectionTransfer
     */
    public function mapAssetEntitiesToAssetCollectionTransfer(
        ObjectCollection $assetEntityCollection,
        AssetCollectionTransfer $assetCollectionTransfer
    ): AssetCollectionTransfer {
        foreach ($assetEntityCollection as $assetEntity) {
            $assetCollectionTransfer->getAssets()->append(
                $this->mapAssetEntityToAssetTransfer($assetEntity, new AssetTransfer()),
            );
        }

        return $assetCollectionTransfer;
    }
}
