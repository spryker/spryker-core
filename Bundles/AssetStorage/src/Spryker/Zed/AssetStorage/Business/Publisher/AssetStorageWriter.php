<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Business\Publisher;

use Generated\Shared\Transfer\AssetConditionsTransfer;
use Generated\Shared\Transfer\AssetCriteriaTransfer;
use Generated\Shared\Transfer\AssetSlotStorageTransfer;
use Generated\Shared\Transfer\AssetStorageCollectionTransfer;
use Generated\Shared\Transfer\AssetStorageTransfer;
use Generated\Shared\Transfer\AssetTransfer;
use Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToAssetFacadeInterface;
use Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToStoreFacadeInterface;
use Spryker\Zed\AssetStorage\Persistence\AssetStorageEntityManagerInterface;
use Spryker\Zed\AssetStorage\Persistence\AssetStorageRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class AssetStorageWriter implements AssetStorageWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToAssetFacadeInterface
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
     * @param \Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToAssetFacadeInterface $assetFacade
     * @param \Spryker\Zed\AssetStorage\Persistence\AssetStorageEntityManagerInterface $assetStorageEntityManager
     * @param \Spryker\Zed\AssetStorage\Persistence\AssetStorageRepositoryInterface $assetStorageRepository
     */
    public function __construct(
        AssetStorageToStoreFacadeInterface $storeFacade,
        AssetStorageToAssetFacadeInterface $assetFacade,
        AssetStorageEntityManagerInterface $assetStorageEntityManager,
        AssetStorageRepositoryInterface $assetStorageRepository
    ) {
        $this->storeFacade = $storeFacade;
        $this->assetFacade = $assetFacade;
        $this->assetStorageEntityManager = $assetStorageEntityManager;
        $this->assetStorageRepository = $assetStorageRepository;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeAssetCollectionByAssetEvents(array $eventEntityTransfers): void
    {
        foreach ($eventEntityTransfers as $eventEntityTransfer) {
            $assetTransfer = (new AssetTransfer())->fromArray(
                $eventEntityTransfer->getAdditionalValues(),
                true,
            );
            $assetTransfer->setIdAsset($eventEntityTransfer->getId());

            $this->publishByAssetTransfer($assetTransfer);
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function deleteAssetCollectionByAssetEvents(array $eventEntityTransfers): void
    {
        foreach ($eventEntityTransfers as $eventEntityTransfer) {
            $assetTransfer = (new AssetTransfer())->fromArray(
                $eventEntityTransfer->getAdditionalValues(),
                true,
            );

            $this->unpublishByAssetTransfer($assetTransfer);
        }
    }

    /**
     * @deprecated Will be removed without replacement.
     *
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

        $this->publishByAssetTransfer($assetTransfer);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
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
        $assetTransfer->setStores([$storeTransfer->getName()]);

        $this->publishByAssetTransfer($assetTransfer);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
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

        $this->unpublishByAssetTransfer($assetTransfer);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
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
        $assetTransfer->setStores([$storeTransfer->getName()]);

        $this->unpublishByAssetTransfer($assetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return void
     */
    protected function publishByAssetTransfer(AssetTransfer $assetTransfer): void
    {
        if ($assetTransfer->getAssetSlot() === null || $assetTransfer->getStores() === []) {
            $assetTransfer = $this->findAsset($assetTransfer);
        }

        $assetSlotStorageTransfers = $this->assetStorageRepository->findAssetStoragesByAssetSlotAndStores(
            $assetTransfer->getAssetSlotOrFail(),
            $assetTransfer->getStores(),
        );

        $existingAssetSlotStorageStoreNames = [];

        foreach ($assetSlotStorageTransfers as $assetSlotStorageTransfer) {
            $existingAssetSlotStorageStoreNames[] = $assetSlotStorageTransfer->getStoreOrFail();
            $this->updateAssetSlotStorageTransfer($assetTransfer, $assetSlotStorageTransfer);
        }

        $storeNamesToCreateAssetSlotStorage = array_diff($assetTransfer->getStores(), $existingAssetSlotStorageStoreNames);
        $newAssetSlotStorageTransfers = $this->createAssetSlotStorageTransfer($assetTransfer, $storeNamesToCreateAssetSlotStorage);

        $assetSlotStorageTransfers = array_merge($assetSlotStorageTransfers, $newAssetSlotStorageTransfers);

        $this->getTransactionHandler()->handleTransaction(function () use ($assetSlotStorageTransfers) {
            foreach ($assetSlotStorageTransfers as $assetSlotStorageTransfer) {
                $this->assetStorageEntityManager->saveAssetSlotStorage($assetSlotStorageTransfer);
            }
        });
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     * @param \Generated\Shared\Transfer\AssetSlotStorageTransfer $assetSlotStorageTransfer
     *
     * @return void
     */
    protected function updateAssetSlotStorageTransfer(
        AssetTransfer $assetTransfer,
        AssetSlotStorageTransfer $assetSlotStorageTransfer
    ): void {
        $assetTransferExistsInAssetSlotStorage = false;

        foreach ($assetSlotStorageTransfer->getDataOrFail()->getAssetsStorage() as $assetStorageTransfer) {
            if ($assetStorageTransfer->getAssetId() !== $assetTransfer->getIdAsset()) {
                continue;
            }

            $assetTransferExistsInAssetSlotStorage = true;
            $this->mapAssetTransferToAssetStorageTransfer($assetTransfer, $assetStorageTransfer);
        }

        if (!$assetTransferExistsInAssetSlotStorage) {
            $assetSlotStorageTransfer->getDataOrFail()->addAssetStorage(
                $this->mapAssetTransferToAssetStorageTransfer($assetTransfer, new AssetStorageTransfer()),
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     * @param array<string> $storeNames
     *
     * @return array<\Generated\Shared\Transfer\AssetSlotStorageTransfer>
     */
    protected function createAssetSlotStorageTransfer(AssetTransfer $assetTransfer, array $storeNames): array
    {
        $assetSlotStorageTransfers = [];

        foreach ($storeNames as $storeName) {
            $assetStorageCollectionTransfer = (new AssetStorageCollectionTransfer())
                ->addAssetStorage(
                    $this->mapAssetTransferToAssetStorageTransfer($assetTransfer, new AssetStorageTransfer()),
                );

            $assetSlotStorageTransfer = (new AssetSlotStorageTransfer())
                ->setAssetSlot($assetTransfer->getAssetSlot())
                ->setStore($storeName)
                ->setData($assetStorageCollectionTransfer);

            $assetSlotStorageTransfers[] = $assetSlotStorageTransfer;
        }

        return $assetSlotStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     * @param \Generated\Shared\Transfer\AssetStorageTransfer $assetStorageTransfer
     *
     * @return \Generated\Shared\Transfer\AssetStorageTransfer
     */
    protected function mapAssetTransferToAssetStorageTransfer(
        AssetTransfer $assetTransfer,
        AssetStorageTransfer $assetStorageTransfer
    ): AssetStorageTransfer {
        return $assetStorageTransfer
            ->setAssetId($assetTransfer->getIdAsset())
            ->setAssetUuid($assetTransfer->getAssetUuid())
            ->setAssetContent($assetTransfer->getAssetContent());
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return void
     */
    protected function unpublishByAssetTransfer(AssetTransfer $assetTransfer): void
    {
        $assetSlotStorageTransfers = $this->assetStorageRepository->findAssetStoragesByAssetSlotAndStores(
            $assetTransfer->getAssetSlot(),
            $assetTransfer->getStores(),
        );

        $this->getTransactionHandler()->handleTransaction(function () use ($assetSlotStorageTransfers, $assetTransfer) {
            foreach ($assetSlotStorageTransfers as $assetSlotStorageTransfer) {
                $this->removeAssetDataFromAssetSlotStorage($assetTransfer, $assetSlotStorageTransfer);
            }
        });
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     * @param \Generated\Shared\Transfer\AssetSlotStorageTransfer $assetSlotStorageTransfer
     *
     * @return void
     */
    protected function removeAssetDataFromAssetSlotStorage(
        AssetTransfer $assetTransfer,
        AssetSlotStorageTransfer $assetSlotStorageTransfer
    ): void {
        $assetStorageTransfers = $assetSlotStorageTransfer->getDataOrFail()->getAssetsStorage();
        $isAssetStorageDataChanged = false;

        foreach ($assetStorageTransfers as $key => $assetStorageTransfer) {
            if ($assetStorageTransfer->getAssetId() !== $assetTransfer->getIdAsset()) {
                continue;
            }

            $assetStorageTransfers->offsetUnset($key);
            $isAssetStorageDataChanged = true;
        }

        if ($assetStorageTransfers->count() === 0) {
            $this->assetStorageEntityManager->deleteAssetSlotStorage($assetSlotStorageTransfer);

            return;
        }

        if (!$isAssetStorageDataChanged) {
            return;
        }

        $this->assetStorageEntityManager->saveAssetSlotStorage($assetSlotStorageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    protected function findAsset(AssetTransfer $assetTransfer): AssetTransfer
    {
        $assetConditionsTransfer = (new AssetConditionsTransfer())->addIdAsset($assetTransfer->getIdAsset());
        $assetCollectionTransfer = $this->assetFacade->getAssetCollection(
            (new AssetCriteriaTransfer())->setAssetConditions($assetConditionsTransfer),
        );

        if ($assetCollectionTransfer->getAssets()->count() === 1) {
            return $assetCollectionTransfer->getAssets()->getIterator()->current();
        }

        return $assetTransfer;
    }
}
