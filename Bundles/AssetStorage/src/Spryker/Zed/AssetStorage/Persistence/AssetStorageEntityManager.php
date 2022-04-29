<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Persistence;

use Generated\Shared\Transfer\AssetTransfer;
use Orm\Zed\AssetStorage\Persistence\SpyAssetSlotStorage;
use Spryker\Zed\AssetStorage\Persistence\Exception\AssetStorageEntityNotFound;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

/**
 * @method \Spryker\Zed\AssetStorage\Persistence\AssetStoragePersistenceFactory getFactory()
 */
class AssetStorageEntityManager extends AbstractEntityManager implements AssetStorageEntityManagerInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const ASSETS_DATA_KEY = 'assets';

    /**
     * @var string
     */
    protected const ASSET_UUID_DATA_KEY = 'assetUuid';

    /**
     * @var string
     */
    protected const ASSET_CONTENT_DATA_KEY = 'assetContent';

    /**
     * @var string
     */
    protected const ASSET_ID_DATA_KEY = 'assetId';

    /**
     * @var string
     */
    protected const ASSET_SLOT_DATA_KEY = 'assetSlot';

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     * @param string $storeName
     * @param array $assetSlotStorageToDelete
     *
     * @return void
     */
    public function createAssetStorage(
        AssetTransfer $assetTransfer,
        string $storeName,
        array $assetSlotStorageToDelete
    ): void {
        $data = [
            static::ASSET_SLOT_DATA_KEY => $assetTransfer->getAssetSlot(),
            static::ASSETS_DATA_KEY => [
                [
                    static::ASSET_ID_DATA_KEY => $assetTransfer->getIdAsset(),
                    static::ASSET_UUID_DATA_KEY => $assetTransfer->getAssetUuid(),
                    static::ASSET_CONTENT_DATA_KEY => $assetTransfer->getAssetContent(),
                ],
            ],
        ];

        $this->getTransactionHandler()->handleTransaction(function () use (
            $assetTransfer,
            $storeName,
            $data,
            $assetSlotStorageToDelete
        ): void {
            $this->executePublishAssetTransaction(
                $assetTransfer,
                $storeName,
                $data,
            );

            $this->removeAssetStorageByIdAsset(
                $assetSlotStorageToDelete,
                $assetTransfer->getIdAsset(),
            );
        });
    }

    /**
     * @param array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer> $assetSlotsStorageEntityTransfers
     * @param int $idAsset
     *
     * @return void
     */
    public function removeAssetStorageByIdAsset(array $assetSlotsStorageEntityTransfers, int $idAsset): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($assetSlotsStorageEntityTransfers, $idAsset): void {
            $this->executeRemoveAssetTransaction($assetSlotsStorageEntityTransfers, $idAsset);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     * @param array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer> $assetSlotStorageToUpdate
     * @param array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer> $assetSlotStorageToDelete
     *
     * @return void
     */
    public function updateAssetStorage(
        AssetTransfer $assetTransfer,
        array $assetSlotStorageToUpdate,
        array $assetSlotStorageToDelete
    ): void {
        $this->getTransactionHandler()->handleTransaction(function () use ($assetTransfer, $assetSlotStorageToUpdate, $assetSlotStorageToDelete): void {
            $this->updateAssetStoragesDataTransaction($assetSlotStorageToUpdate, $assetTransfer);
            $this->executeRemoveAssetTransaction($assetSlotStorageToDelete, $assetTransfer->getIdAsset());
        });
    }

    /**
     * @param \Orm\Zed\AssetStorage\Persistence\SpyAssetSlotStorage $assetSlotStorageEntity
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return bool
     */
    protected function updateData(
        SpyAssetSlotStorage $assetSlotStorageEntity,
        AssetTransfer $assetTransfer
    ): bool {
        $data = $assetSlotStorageEntity->getData();

        $isUpdated = false;
        foreach ($data[static::ASSETS_DATA_KEY] as $key => $asset) {
            if ($asset[static::ASSET_ID_DATA_KEY] !== $assetTransfer->getIdAsset()) {
                continue;
            }
            $data[static::ASSETS_DATA_KEY][$key][static::ASSET_CONTENT_DATA_KEY] = $assetTransfer->getAssetContent();
            $assetSlotStorageEntity->setData($data);
            $assetSlotStorageEntity->save();
            $isUpdated = true;
        }

        return $isUpdated;
    }

    /**
     * @param \Orm\Zed\AssetStorage\Persistence\SpyAssetSlotStorage $assetSlotStorageEntity
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return void
     */
    protected function createData(
        SpyAssetSlotStorage $assetSlotStorageEntity,
        AssetTransfer $assetTransfer
    ): void {
        $data = $assetSlotStorageEntity->getData();

        $data[static::ASSETS_DATA_KEY][] = [
            static::ASSET_ID_DATA_KEY => $assetTransfer->getIdAsset(),
            static::ASSET_UUID_DATA_KEY => $assetTransfer->getAssetUuid(),
            static::ASSET_CONTENT_DATA_KEY => $assetTransfer->getAssetContent(),
        ];
        $assetSlotStorageEntity->setData($data);
        $assetSlotStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     * @param string $storeName
     * @param array<string, mixed> $data
     *
     * @return void
     */
    protected function executePublishAssetTransaction(
        AssetTransfer $assetTransfer,
        string $storeName,
        array $data
    ): void {
        $assetSlotStorage = $this->getFactory()->createSpyAssetSlotStorage();

        $assetSlotStorage
            ->setStore($storeName)
            ->setAssetSlot($assetTransfer->getAssetSlot())
            ->setData($data);

        $assetSlotStorage->save();
    }

    /**
     * @param array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer> $assetSlotsStorageEntityTransfers
     * @param int $idAsset
     *
     * @return void
     */
    protected function executeRemoveAssetTransaction(array $assetSlotsStorageEntityTransfers, int $idAsset): void
    {
        foreach ($assetSlotsStorageEntityTransfers as $assetSlotsStorageEntityTransfer) {
            /** @var array $data */
            $data = $assetSlotsStorageEntityTransfer->getData();

            foreach ($data[static::ASSETS_DATA_KEY] as $key => $asset) {
                if ($asset[static::ASSET_ID_DATA_KEY] !== $idAsset) {
                    continue;
                }
                unset($data[static::ASSETS_DATA_KEY][$key]);
                $assetStorageEntity = $this->getAssetStorageEntityById(
                    $assetSlotsStorageEntityTransfer->getIdAssetSlotStorage(),
                );

                $assetStorageEntity->setData($data);

                $assetStorageEntity->save();
            }
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer> $assetSlotStorageEntityTransfers
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return void
     */
    protected function updateAssetStoragesDataTransaction(
        array $assetSlotStorageEntityTransfers,
        AssetTransfer $assetTransfer
    ): void {
        foreach ($assetSlotStorageEntityTransfers as $assetSlotStorageEntityTransfer) {
            $assetSlotStorageEntityTransfer = $this->getAssetStorageEntityById(
                $assetSlotStorageEntityTransfer->getIdAssetSlotStorage(),
            );

            $isUpdated = $this->updateData($assetSlotStorageEntityTransfer, $assetTransfer);

            if (!$isUpdated) {
                $this->createData($assetSlotStorageEntityTransfer, $assetTransfer);
            }
        }
    }

    /**
     * @param int $idAssetSlotStorage
     *
     * @throws \Spryker\Zed\AssetStorage\Persistence\Exception\AssetStorageEntityNotFound
     *
     * @return \Orm\Zed\AssetStorage\Persistence\SpyAssetSlotStorage
     */
    protected function getAssetStorageEntityById(int $idAssetSlotStorage): SpyAssetSlotStorage
    {
        $assetSlotStorageEntity = $this->getFactory()
            ->createAssetStorageQuery()
            ->findOneByIdAssetSlotStorage($idAssetSlotStorage);

        if (!$assetSlotStorageEntity) {
            throw new AssetStorageEntityNotFound($idAssetSlotStorage);
        }

        return $assetSlotStorageEntity;
    }
}
