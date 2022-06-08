<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Persistence\Mapper;

use Generated\Shared\Transfer\AssetSlotStorageTransfer;
use Generated\Shared\Transfer\AssetStorageCollectionTransfer;
use Generated\Shared\Transfer\AssetStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\AssetStorage\Persistence\SpyAssetSlotStorage;
use Propel\Runtime\Collection\ObjectCollection;

class AssetStorageMapper
{
    /**
     * @var string
     */
    protected const ASSETS_DATA_KEY = 'assets';

    /**
     * @param \Generated\Shared\Transfer\AssetSlotStorageTransfer $assetSlotStorageTransfer
     * @param \Orm\Zed\AssetStorage\Persistence\SpyAssetSlotStorage $assetSlotStorageEntity
     *
     * @return \Orm\Zed\AssetStorage\Persistence\SpyAssetSlotStorage
     */
    public function mapAssetSlotStorageTransferToEntity(
        AssetSlotStorageTransfer $assetSlotStorageTransfer,
        SpyAssetSlotStorage $assetSlotStorageEntity
    ): SpyAssetSlotStorage {
        $assetSlotStorageEntity->fromArray($assetSlotStorageTransfer->toArray());

        $assetsData = [];

        foreach ($assetSlotStorageTransfer->getDataOrFail()->getAssetsStorage() as $assetStorageTransfer) {
            $assetsData[] = $assetStorageTransfer->modifiedToArrayNotRecursiveCamelCased();
        }

        return $assetSlotStorageEntity->setData([
            'assetSlot' => $assetSlotStorageTransfer->getAssetSlot(),
            static::ASSETS_DATA_KEY => $assetsData,
        ]);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\AssetStorage\Persistence\SpyAssetSlotStorage> $assetSlotStorageEntities
     *
     * @return array<\Generated\Shared\Transfer\AssetSlotStorageTransfer>
     */
    public function mapAssetSlotStorageEntitiesToTransfers(ObjectCollection $assetSlotStorageEntities): array
    {
        $assetSlotStorageTransfers = [];

        foreach ($assetSlotStorageEntities as $assetSlotStorageEntity) {
            $assetSlotStorageTransfers[] = $this->mapAssetSlotStorageEntityToTransfer(
                $assetSlotStorageEntity,
                new AssetSlotStorageTransfer(),
            );
        }

        return $assetSlotStorageTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer> $assetSlotStorageEntityTransfers
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function mapAssetSlotStorageEntityTransfersToSynchronizationTransfers(
        array $assetSlotStorageEntityTransfers
    ): array {
        $synchronizationDataTransfers = [];

        foreach ($assetSlotStorageEntityTransfers as $assetSlotStorageEntityTransfer) {
            $synchronizationDataTransfer = (new SynchronizationDataTransfer())
                ->setKey($assetSlotStorageEntityTransfer->getAssetSlot())
                ->setStore($assetSlotStorageEntityTransfer->getStore())
                ->setData($assetSlotStorageEntityTransfer->getData());

            $synchronizationDataTransfers[] = $synchronizationDataTransfer;
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Orm\Zed\AssetStorage\Persistence\SpyAssetSlotStorage $assetSlotStorageEntity
     * @param \Generated\Shared\Transfer\AssetSlotStorageTransfer $assetSlotStorageTransfer
     *
     * @return \Generated\Shared\Transfer\AssetSlotStorageTransfer
     */
    protected function mapAssetSlotStorageEntityToTransfer(
        SpyAssetSlotStorage $assetSlotStorageEntity,
        AssetSlotStorageTransfer $assetSlotStorageTransfer
    ): AssetSlotStorageTransfer {
        $assetSlotStorageTransfer->fromArray($assetSlotStorageEntity->toArray(), true);

        $assetStorageCollectionTransfer = (new AssetStorageCollectionTransfer());

        foreach ($assetSlotStorageEntity->getData()[static::ASSETS_DATA_KEY] as $assetData) {
            $assetStorageCollectionTransfer->addAssetStorage(
                (new AssetStorageTransfer())->fromArray($assetData, true),
            );
        }

        return $assetSlotStorageTransfer->setData($assetStorageCollectionTransfer);
    }
}
