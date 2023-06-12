<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Business\Updater;

use Generated\Shared\Transfer\AssetTransfer;
use Generated\Shared\Transfer\AssetUpdatedTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Shared\Asset\AssetConfig;
use Spryker\Zed\Asset\Business\Exception\InvalidAssetException;
use Spryker\Zed\Asset\Dependency\Facade\AssetToEventFacadeInterface;
use Spryker\Zed\Asset\Dependency\Facade\AssetToStoreInterface;
use Spryker\Zed\Asset\Persistence\AssetEntityManagerInterface;
use Spryker\Zed\Asset\Persistence\AssetRepositoryInterface;

class AssetUpdater implements AssetUpdaterInterface
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
     * @var \Spryker\Zed\Asset\Dependency\Facade\AssetToStoreInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Asset\Dependency\Facade\AssetToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\Asset\Persistence\AssetRepositoryInterface $assetRepository
     * @param \Spryker\Zed\Asset\Persistence\AssetEntityManagerInterface $assetEntityManager
     * @param \Spryker\Zed\Asset\Dependency\Facade\AssetToStoreInterface $storeFacade
     * @param \Spryker\Zed\Asset\Dependency\Facade\AssetToEventFacadeInterface $eventFacade
     */
    public function __construct(
        AssetRepositoryInterface $assetRepository,
        AssetEntityManagerInterface $assetEntityManager,
        AssetToStoreInterface $storeFacade,
        AssetToEventFacadeInterface $eventFacade
    ) {
        $this->assetRepository = $assetRepository;
        $this->assetEntityManager = $assetEntityManager;
        $this->storeFacade = $storeFacade;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AssetUpdatedTransfer $assetUpdatedTransfer
     *
     * @throws \Spryker\Zed\Asset\Business\Exception\InvalidAssetException
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function updateAsset(AssetUpdatedTransfer $assetUpdatedTransfer): AssetTransfer
    {
        $messageAttributes = $assetUpdatedTransfer->getMessageAttributesOrFail();

        $assetUpdatedTransfer
            ->requireAssetView()
            ->requireAssetIdentifier()
            ->requireAssetSlot();

        $storeTransfer = $this->storeFacade->getStoreByStoreReference($messageAttributes->getStoreReferenceOrFail());

        $assetTransfer = $this->assetRepository->findAssetByAssetUuid($assetUpdatedTransfer->getAssetIdentifierOrFail());
        if ($assetTransfer === null) {
            throw new InvalidAssetException('This asset doesn\'t exist in DB.');
        }

        $previousStateAssetTransfer = clone $assetTransfer;
        $assetTransfer->setAssetContent($assetUpdatedTransfer->getAssetView())
            ->setAssetSlot($assetUpdatedTransfer->getAssetSlot())
            ->setIsActive(true)
            ->setLastMessageTimestamp($assetUpdatedTransfer->getMessageAttributesOrFail()->getTimestamp());

        $assetTransfer = $this->assetEntityManager->saveAssetWithStores($assetTransfer, [$storeTransfer]);

        $assetTransfer->setStores([$storeTransfer->getNameOrFail()]);

        $this->sendEvents($assetTransfer, $previousStateAssetTransfer);

        return $assetTransfer;
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
            array_diff($previousStateAssetTransfer->getStores(), $assetTransfer->getStores())
            || $previousStateAssetTransfer->getAssetSlot() !== $assetTransfer->getAssetSlot()
        ) {
            $unpublishEventEntityTransfer = (new EventEntityTransfer())
                ->setId($previousStateAssetTransfer->getIdAsset())
                ->setAdditionalValues($previousStateAssetTransfer->toArray());

            $this->eventFacade->trigger(AssetConfig::ASSET_UNPUBLISH, $unpublishEventEntityTransfer);
        }

        $publishEventEntityTransfer = (new EventEntityTransfer())
            ->setId($assetTransfer->getIdAsset())
            ->setAdditionalValues($assetTransfer->toArray());

        $this->eventFacade->trigger(AssetConfig::ASSET_PUBLISH, $publishEventEntityTransfer);
    }
}
