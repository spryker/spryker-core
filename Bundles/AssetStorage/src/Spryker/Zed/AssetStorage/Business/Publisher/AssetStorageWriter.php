<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Business\Publisher;

use Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToAssetInterface;
use Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToStoreFacadeInterface;
use Spryker\Zed\AssetStorage\Persistence\AssetStorageEntityManagerInterface;
use Spryker\Zed\AssetStorage\Persistence\AssetStorageRepositoryInterface;

class AssetStorageWriter implements AssetStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToAssetInterface
     */
    protected $assetFacade;

    /**
     * @var \Spryker\Zed\AssetStorage\Persistence\AssetStorageEntityManagerInterface
     */
    protected $assetStorageEntityManager;

    /**
     * @var \Spryker\Zed\AssetStorage\Persistence\AssetStorageRepositoryInterface
     */
    protected $assetStorageRepository;

    /**
     * @param \Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToAssetInterface $assetFacade
     * @param \Spryker\Zed\AssetStorage\Persistence\AssetStorageEntityManagerInterface $assetStorageEntityManager
     * @param \Spryker\Zed\AssetStorage\Persistence\AssetStorageRepositoryInterface $assetStorageRepository
     */
    public function __construct(
        AssetStorageToStoreFacadeInterface $storeFacade,
        AssetStorageToAssetInterface $assetFacade,
        AssetStorageEntityManagerInterface $assetStorageEntityManager,
        AssetStorageRepositoryInterface $assetStorageRepository
    ) {
        $this->storeFacade = $storeFacade;
        $this->assetFacade = $assetFacade;
        $this->assetStorageEntityManager = $assetStorageEntityManager;
        $this->assetStorageRepository = $assetStorageRepository;
    }

    /**
     * @param int $idAsset
     *
     * @return void
     */
    public function publish(int $idAsset): void
    {
        $assetTransfer = $this->assetFacade->findAssetById($idAsset);

        if (!$assetTransfer) {
            return;
        }

        $assetSlotStorageToUpdate = $this->assetStorageRepository->findAssetStoragesByAssetSlotAndStores($assetTransfer->getAssetSlot(), $assetTransfer->getStores());
        $assetSlotStorageToDelete = $this->assetStorageRepository->findAssetStoragesWithAssetSlotNotEqualAndByStores($assetTransfer->getAssetSlot(), $assetTransfer->getStores());

        if (count($assetSlotStorageToUpdate)) {
            $this->assetStorageEntityManager->updateAssetStorage(
                $assetTransfer,
                $assetSlotStorageToUpdate,
                $assetSlotStorageToDelete,
            );

            return;
        }

        foreach ($assetTransfer->getStores() as $storeName) {
            $this->assetStorageEntityManager->createAssetStorage(
                $assetTransfer,
                $storeName,
                $assetSlotStorageToDelete,
            );
        }
    }

    /**
     * @param int $idAsset
     * @param int $idStore
     *
     * @return void
     */
    public function publishStoreRelation(int $idAsset, int $idStore): void
    {
        $assetTransfer = $this->assetFacade->findAssetById($idAsset);

        if (!$assetTransfer) {
            return;
        }

        $storeTransfer = $this->storeFacade->getStoreById($idStore);

        $assetSlotStorageEntityTransfers = $this->assetStorageRepository->findAssetStoragesByAssetSlotAndStores(
            $assetTransfer->getAssetSlot(),
            [$storeTransfer->getName()],
        );

        if (!count($assetSlotStorageEntityTransfers)) {
            $this->assetStorageEntityManager->createAssetStorage($assetTransfer, $storeTransfer->getName(), []);

            return;
        }

        $this->assetStorageEntityManager->updateAssetStorage($assetTransfer, $assetSlotStorageEntityTransfers, []);
    }

    /**
     * @param int $idAsset
     *
     * @return void
     */
    public function unpublish(int $idAsset): void
    {
        $assetTransfer = $this->assetFacade->findAssetById($idAsset);
        if (!$assetTransfer) {
            return;
        }

        $assetSlotStorageEntityTransfers = $this->assetStorageRepository->findAssetStoragesByAssetSlot(
            $assetTransfer->getAssetSlot(),
        );

        $this->removeAssetsFromStorageData($assetSlotStorageEntityTransfers, $idAsset);
    }

    /**
     * @param int $idAsset
     * @param int $idStore
     *
     * @return void
     */
    public function unpublishStoreRelation(int $idAsset, int $idStore): void
    {
        $assetTransfer = $this->assetFacade->findAssetById($idAsset);

        if (!$assetTransfer) {
            return;
        }

        $storeTransfer = $this->storeFacade->getStoreById($idStore);

        $assetSlotStorageEntityTransfers = $this->assetStorageRepository->findAssetStoragesByAssetSlotAndStores(
            $assetTransfer->getAssetSlot(),
            [$storeTransfer->getName()],
        );

        $this->removeAssetsFromStorageData($assetSlotStorageEntityTransfers, $idAsset);
    }

    /**
     * @param array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer> $assetSlotsStorageEntityTransfers
     * @param int $idAsset
     *
     * @return void
     */
    protected function removeAssetsFromStorageData(
        array $assetSlotsStorageEntityTransfers,
        int $idAsset
    ): void {
        $this->assetStorageEntityManager->removeAssetStorageByIdAsset(
            $assetSlotsStorageEntityTransfers,
            $idAsset,
        );
    }
}
