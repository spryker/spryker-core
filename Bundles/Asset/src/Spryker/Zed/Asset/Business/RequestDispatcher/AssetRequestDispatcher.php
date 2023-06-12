<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Business\RequestDispatcher;

use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetDeletedTransfer;
use Generated\Shared\Transfer\AssetTransfer;
use Generated\Shared\Transfer\AssetUpdatedTransfer;
use Spryker\Zed\Asset\Business\Creator\AssetCreatorInterface;
use Spryker\Zed\Asset\Business\Deleter\AssetDeleterInterface;
use Spryker\Zed\Asset\Business\TimeStamp\AssetTimeStampInterface;
use Spryker\Zed\Asset\Business\Updater\AssetUpdaterInterface;
use Spryker\Zed\Asset\Persistence\AssetRepositoryInterface;

class AssetRequestDispatcher implements AssetRequestDispatcherInterface
{
    /**
     * @var \Spryker\Zed\Asset\Persistence\AssetRepositoryInterface
     */
    protected $assetRepository;

    /**
     * @var \Spryker\Zed\Asset\Business\Creator\AssetCreatorInterface
     */
    protected $assetCreator;

    /**
     * @var \Spryker\Zed\Asset\Business\Updater\AssetUpdaterInterface
     */
    protected $assetUpdater;

    /**
     * @var \Spryker\Zed\Asset\Business\Deleter\AssetDeleterInterface
     */
    protected $assetDeleter;

    /**
     * @var \Spryker\Zed\Asset\Business\TimeStamp\AssetTimeStampInterface
     */
    protected $assetTimeStamp;

    /**
     * @param \Spryker\Zed\Asset\Persistence\AssetRepositoryInterface $assetRepository
     * @param \Spryker\Zed\Asset\Business\Creator\AssetCreatorInterface $assetCreator
     * @param \Spryker\Zed\Asset\Business\Updater\AssetUpdaterInterface $assetUpdater
     * @param \Spryker\Zed\Asset\Business\Deleter\AssetDeleterInterface $assetDeleter
     * @param \Spryker\Zed\Asset\Business\TimeStamp\AssetTimeStampInterface $assetTimeStamp
     */
    public function __construct(
        AssetRepositoryInterface $assetRepository,
        AssetCreatorInterface $assetCreator,
        AssetUpdaterInterface $assetUpdater,
        AssetDeleterInterface $assetDeleter,
        AssetTimeStampInterface $assetTimeStamp
    ) {
        $this->assetRepository = $assetRepository;
        $this->assetCreator = $assetCreator;
        $this->assetUpdater = $assetUpdater;
        $this->assetDeleter = $assetDeleter;
        $this->assetTimeStamp = $assetTimeStamp;
    }

    /**
     * @param \Generated\Shared\Transfer\AssetAddedTransfer $assetAddedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function dispatchAssetAddedTransferRequest(AssetAddedTransfer $assetAddedTransfer): AssetTransfer
    {
        $assetTransfer = $this->assetRepository->findAssetByAssetUuid($assetAddedTransfer->getAssetIdentifierOrFail());

        $messageAttributes = $assetAddedTransfer->getMessageAttributesOrFail();
        $assetAddedTransfer->setMessageAttributes($this->assetTimeStamp->updateMessageAttributesTimestampIfRequired($messageAttributes));

        if ($assetTransfer === null) {
            return $this->assetCreator->addAsset($assetAddedTransfer);
        }

        if (!$this->assetTimeStamp->shouldTransferMessageBeProcessed($assetTransfer, $assetAddedTransfer->getMessageAttributesOrFail())) {
            return $assetTransfer;
        }
        $assetUpdatedTransfer = (new AssetUpdatedTransfer())->fromArray($assetAddedTransfer->toArray());

        return $this->assetUpdater->updateAsset($assetUpdatedTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetUpdatedTransfer $assetUpdatedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function dispatchAssetUpdatedTransferRequest(AssetUpdatedTransfer $assetUpdatedTransfer): AssetTransfer
    {
        $assetTransfer = $this->assetRepository->findAssetByAssetUuid($assetUpdatedTransfer->getAssetIdentifierOrFail());

        $messageAttributes = $assetUpdatedTransfer->getMessageAttributesOrFail();
        $assetUpdatedTransfer->setMessageAttributes($this->assetTimeStamp->updateMessageAttributesTimestampIfRequired($messageAttributes));

        if ($assetTransfer === null) {
            $assetCreatedTransfer = (new AssetAddedTransfer())->fromArray($assetUpdatedTransfer->toArray());

            return $this->assetCreator->addAsset($assetCreatedTransfer);
        }

        if (!$this->assetTimeStamp->shouldTransferMessageBeProcessed($assetTransfer, $assetUpdatedTransfer->getMessageAttributesOrFail())) {
            return $assetTransfer;
        }

        return $this->assetUpdater->updateAsset($assetUpdatedTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetDeletedTransfer $assetDeletedTransfer
     *
     * @return void
     */
    public function dispatchAssetDeletedTransferRequest(AssetDeletedTransfer $assetDeletedTransfer): void
    {
        $assetTransfer = $this->assetRepository->findAssetByAssetUuid($assetDeletedTransfer->getAssetIdentifierOrFail());

        $messageAttributes = $assetDeletedTransfer->getMessageAttributesOrFail();
        $assetDeletedTransfer->setMessageAttributes($this->assetTimeStamp->updateMessageAttributesTimestampIfRequired($messageAttributes));

        if (
            $assetTransfer === null
            || $this->assetTimeStamp->shouldTransferMessageBeProcessed($assetTransfer, $assetDeletedTransfer->getMessageAttributesOrFail())
        ) {
            $this->assetDeleter->deleteAsset($assetDeletedTransfer);
        }
    }
}
