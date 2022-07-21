<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Persistence;

use Generated\Shared\Transfer\AssetTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Asset\Persistence\AssetPersistenceFactory getFactory()
 */
class AssetEntityManager extends AbstractEntityManager implements AssetEntityManagerInterface
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
    ): AssetTransfer {
        $assetTransfer = $this->saveAsset($assetTransfer);

        return $this->saveAssetStoreByAssetTransfer($assetTransfer, $storeTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function saveAsset(AssetTransfer $assetTransfer): AssetTransfer
    {
        $assetTransfer->requireAssetUuid()
            ->requireAssetName()
            ->requireAssetContent()
            ->requireAssetSlot();

        $assetEntity = $this->getFactory()
            ->createAssetQuery()
            ->filterByAssetUuid($assetTransfer->getAssetUuid())
            ->findOneOrCreate();

        $assetEntity = $assetEntity
            ->setAssetUuid((string)$assetTransfer->getAssetUuid())
            ->setAssetContent((string)$assetTransfer->getAssetContent())
            ->setAssetName((string)$assetTransfer->getAssetName())
            ->setAssetSlot((string)$assetTransfer->getAssetSlot());

        $assetEntity->save();

        $assetTransfer->setIdAsset($assetEntity->getIdAsset());

        return $assetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    protected function saveAssetStoreByAssetTransfer(
        AssetTransfer $assetTransfer,
        array $storeTransfers
    ): AssetTransfer {
        $assetTransfer->requireIdAsset();

        $storeTransferIds = [];
        foreach ($storeTransfers as $storeTransfer) {
            $this->saveAssetStore(
                (int)$assetTransfer->getIdAsset(),
                (int)$storeTransfer->getIdStore(),
            );

            $storeTransferIds[] = $storeTransfer->getIdStoreOrFail();
        }
        $this->deleteStoresNotInStoreIdList($storeTransferIds, $assetTransfer->getIdAssetOrFail());

        return $assetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return void
     */
    public function deleteAsset(AssetTransfer $assetTransfer): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $assetStoreObjectCollection */
        $assetStoreObjectCollection = $this->getFactory()
            ->createAssetStoreQuery()
            ->findByFkAsset($assetTransfer->getIdAssetOrFail());

        $assetStoreObjectCollection->delete();

        $assetEntity = $this->getFactory()
            ->createAssetQuery()
            ->findOneByIdAsset($assetTransfer->getIdAssetOrFail());

        if ($assetEntity !== null) {
            $assetEntity->delete();
        }
    }

    /**
     * @param int $fkAsset
     * @param int $fkStore
     *
     * @return void
     */
    protected function saveAssetStore(int $fkAsset, int $fkStore): void
    {
        $assetStoreEntity = $this->getFactory()
            ->createAssetStoreQuery()
            ->filterByFkAsset($fkAsset)
            ->filterByFkStore($fkStore)
            ->findOneOrCreate();

        $assetStoreEntity->setFkAsset($fkAsset)->setFkStore($fkStore);
        $assetStoreEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return void
     */
    public function deleteAssetStores(AssetTransfer $assetTransfer, array $storeTransfers): void
    {
        $assetTransfer->requireIdAsset();

        $storeIds = [];
        foreach ($storeTransfers as $store) {
            $storeIds[] = $store->getIdStore();
        }

        $this->getFactory()->createAssetStoreQuery()
            ->filterByFkAsset($assetTransfer->getIdAsset())
            ->filterByFkStore_In($storeIds)
            ->find()
            ->delete();
    }

    /**
     * @param array<int> $storeTransferIds
     * @param int $idAsset
     *
     * @return void
     */
    protected function deleteStoresNotInStoreIdList(array $storeTransferIds, int $idAsset): void
    {
        $this->getFactory()->createAssetStoreQuery()
            ->filterByFkAsset($idAsset)
            ->filterByFkStore($storeTransferIds, Criteria::NOT_IN)
            ->find()
            ->delete();
    }
}
