<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Business\Deleter;

use Generated\Shared\Transfer\AssetDeletedTransfer;
use Generated\Shared\Transfer\AssetTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Shared\Asset\AssetConfig;
use Spryker\Zed\Asset\Dependency\Facade\AssetToEventFacadeInterface;
use Spryker\Zed\Asset\Dependency\Facade\AssetToStoreReferenceInterface;
use Spryker\Zed\Asset\Persistence\AssetEntityManagerInterface;
use Spryker\Zed\Asset\Persistence\AssetRepositoryInterface;

class AssetDeleter implements AssetDeleterInterface
{
    /**
     * @var \Spryker\Zed\Asset\Persistence\AssetRepositoryInterface
     */
    protected $assetRepository;

    /**
     * @var \Spryker\Zed\Asset\Persistence\AssetEntityManagerInterface
     */
    protected $assetEntityManager;

    /**
     * @var \Spryker\Zed\Asset\Dependency\Facade\AssetToStoreReferenceInterface
     */
    protected $storeReferenceFacade;

    /**
     * @var \Spryker\Zed\Asset\Dependency\Facade\AssetToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\Asset\Persistence\AssetRepositoryInterface $assetRepository
     * @param \Spryker\Zed\Asset\Persistence\AssetEntityManagerInterface $assetEntityManager
     * @param \Spryker\Zed\Asset\Dependency\Facade\AssetToStoreReferenceInterface $storeReferenceFacade
     * @param \Spryker\Zed\Asset\Dependency\Facade\AssetToEventFacadeInterface $eventFacade
     */
    public function __construct(
        AssetRepositoryInterface $assetRepository,
        AssetEntityManagerInterface $assetEntityManager,
        AssetToStoreReferenceInterface $storeReferenceFacade,
        AssetToEventFacadeInterface $eventFacade
    ) {
        $this->assetRepository = $assetRepository;
        $this->assetEntityManager = $assetEntityManager;
        $this->storeReferenceFacade = $storeReferenceFacade;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AssetDeletedTransfer $assetDeletedTransfer
     *
     * @return void
     */
    public function deleteAsset(AssetDeletedTransfer $assetDeletedTransfer): void
    {
        $assetDeletedTransfer->requireAssetIdentifier();

        $storeTransfer = $this->storeReferenceFacade->getStoreByStoreReference(
            $assetDeletedTransfer->getMessageAttributesOrFail()->getStoreReferenceOrFail(),
        );
        $assetTransfer = $this->assetRepository->findAssetByAssetUuid(
            (string)$assetDeletedTransfer->getAssetIdentifier(),
        );

        if (!$assetTransfer) {
            return;
        }

        $this->assetEntityManager->deleteAsset($assetTransfer);
        $this->assetEntityManager->deleteAssetStores($assetTransfer, [$storeTransfer]);

        $assetTransfer->setStores([$storeTransfer->getNameOrFail()]);

        $this->sendEvent($assetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return void
     */
    protected function sendEvent(AssetTransfer $assetTransfer): void
    {
        $eventEntityTransfer = (new EventEntityTransfer())
            ->setId($assetTransfer->getIdAsset())
            ->setAdditionalValues($assetTransfer->toArray());

        $this->eventFacade->trigger(AssetConfig::ASSET_UNPUBLISH, $eventEntityTransfer);
    }
}
