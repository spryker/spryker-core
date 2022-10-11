<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Business\Creator;

use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Shared\Asset\AssetConfig;
use Spryker\Zed\Asset\Business\Exception\InvalidAssetException;
use Spryker\Zed\Asset\Business\Mapper\AssetMapperInterface;
use Spryker\Zed\Asset\Dependency\Facade\AssetToEventFacadeInterface;
use Spryker\Zed\Asset\Dependency\Facade\AssetToStoreInterface;
use Spryker\Zed\Asset\Persistence\AssetEntityManagerInterface;
use Spryker\Zed\Asset\Persistence\AssetRepositoryInterface;

class AssetCreator implements AssetCreatorInterface
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
     * @var \Spryker\Zed\Asset\Business\Mapper\AssetMapperInterface
     */
    protected $assetMapper;

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
     * @param \Spryker\Zed\Asset\Business\Mapper\AssetMapperInterface $assetMapper
     * @param \Spryker\Zed\Asset\Dependency\Facade\AssetToStoreInterface $storeFacade
     * @param \Spryker\Zed\Asset\Dependency\Facade\AssetToEventFacadeInterface $eventFacade
     */
    public function __construct(
        AssetRepositoryInterface $assetRepository,
        AssetEntityManagerInterface $assetEntityManager,
        AssetMapperInterface $assetMapper,
        AssetToStoreInterface $storeFacade,
        AssetToEventFacadeInterface $eventFacade
    ) {
        $this->assetRepository = $assetRepository;
        $this->assetEntityManager = $assetEntityManager;
        $this->assetMapper = $assetMapper;
        $this->storeFacade = $storeFacade;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AssetAddedTransfer $assetAddedTransfer
     *
     * @throws \Spryker\Zed\Asset\Business\Exception\InvalidAssetException
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function addAsset(AssetAddedTransfer $assetAddedTransfer): AssetTransfer
    {
        $messageAttributes = $assetAddedTransfer->getMessageAttributesOrFail();

        $assetAddedTransfer
            ->requireAssetView()
            ->requireAssetName()
            ->requireAssetIdentifier()
            ->requireAssetSlot();

        $storeTransfer = $this->storeFacade->getStoreByStoreReference($messageAttributes->getStoreReferenceOrFail());
        $assetTransfer = $this->assetRepository
            ->findAssetByAssetUuid((string)$assetAddedTransfer->getAssetIdentifier());

        if ($assetTransfer !== null) {
            throw new InvalidAssetException('This asset already exists in DB.');
        }

        $assetTransfer = $this->assetMapper->mapAssetAddedTransferToAssetTransfer(
            $assetAddedTransfer,
            new AssetTransfer(),
        );

        $assetTransfer = $this->assetEntityManager->saveAssetWithStores($assetTransfer, [$storeTransfer]);

        $assetTransfer->setStores([$storeTransfer->getNameOrFail()]);

        $this->sendEvent($assetTransfer);

        return $assetTransfer;
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

        $this->eventFacade->trigger(AssetConfig::ASSET_PUBLISH, $eventEntityTransfer);
    }
}
