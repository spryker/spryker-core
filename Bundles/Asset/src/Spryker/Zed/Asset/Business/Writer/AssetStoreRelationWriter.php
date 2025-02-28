<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Business\Writer;

use Generated\Shared\Transfer\AssetConditionsTransfer;
use Generated\Shared\Transfer\AssetCriteriaTransfer;
use Generated\Shared\Transfer\AssetTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Shared\Asset\AssetConfig;
use Spryker\Zed\Asset\Dependency\Facade\AssetToEventFacadeInterface;
use Spryker\Zed\Asset\Dependency\Facade\AssetToStoreInterface;
use Spryker\Zed\Asset\Persistence\AssetEntityManagerInterface;
use Spryker\Zed\Asset\Persistence\AssetRepositoryInterface;

class AssetStoreRelationWriter implements AssetStoreRelationWriterInterface
{
    /**
     * @param \Spryker\Zed\Asset\Persistence\AssetRepositoryInterface $assetRepository
     * @param \Spryker\Zed\Asset\Persistence\AssetEntityManagerInterface $assetEntityManager
     * @param \Spryker\Zed\Asset\Dependency\Facade\AssetToStoreInterface $storeFacade
     * @param \Spryker\Zed\Asset\Dependency\Facade\AssetToEventFacadeInterface $eventFacade
     */
    public function __construct(
        protected AssetRepositoryInterface $assetRepository,
        protected AssetEntityManagerInterface $assetEntityManager,
        protected AssetToStoreInterface $storeFacade,
        protected AssetToEventFacadeInterface $eventFacade
    ) {
    }

    /**
     * @return void
     */
    public function refreshAllAssetStoreRelations(): void
    {
        $assetCriteriaTransfer = (new AssetCriteriaTransfer())
            ->setAssetConditions(
                (new AssetConditionsTransfer())->setWithStores(true),
            );
        $assetCollectionTransfer = $this->assetRepository->getAssetCollection($assetCriteriaTransfer);

        foreach ($assetCollectionTransfer->getAssets() as $assetTransfer) {
            $this->refreshAssetStoreRelationsByAsset($assetTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return void
     */
    protected function refreshAssetStoreRelationsByAsset(AssetTransfer $assetTransfer): void
    {
        $storeTransfers = $this->storeFacade->getAllStores();
        $previousStateAssetTransfer = clone $assetTransfer;

        $assetTransfer = $this->assetEntityManager
            ->saveAssetStoreRelationsByAsset($assetTransfer, $storeTransfers);

        $storeTransferNames = [];
        foreach ($storeTransfers as $storeTransfer) {
            $storeTransferNames[] = $storeTransfer->getNameOrFail();
        }

        $assetTransfer->setStores($storeTransferNames);

        $this->sendEvents($assetTransfer, $previousStateAssetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     * @param \Generated\Shared\Transfer\AssetTransfer $previousStateAssetTransfer
     *
     * @return void
     */
    protected function sendEvents(AssetTransfer $assetTransfer, AssetTransfer $previousStateAssetTransfer): void
    {
        if (
            !array_diff($assetTransfer->getStores(), $previousStateAssetTransfer->getStores())
            && !array_diff($previousStateAssetTransfer->getStores(), $assetTransfer->getStores())
        ) {
            return;
        }

        $publishEventEntityTransfer = (new EventEntityTransfer())
            ->setId($assetTransfer->getIdAsset())
            ->setAdditionalValues($assetTransfer->toArray());

        $this->eventFacade->trigger(AssetConfig::ASSET_PUBLISH, $publishEventEntityTransfer);
    }
}
