<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Business\Deleter;

use Generated\Shared\Transfer\AssetDeletedTransfer;
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
    private $storeReferenceFacade;

    /**
     * @param \Spryker\Zed\Asset\Persistence\AssetRepositoryInterface $assetRepository
     * @param \Spryker\Zed\Asset\Persistence\AssetEntityManagerInterface $assetEntityManager
     * @param \Spryker\Zed\Asset\Dependency\Facade\AssetToStoreReferenceInterface $storeReferenceFacade
     */
    public function __construct(
        AssetRepositoryInterface $assetRepository,
        AssetEntityManagerInterface $assetEntityManager,
        AssetToStoreReferenceInterface $storeReferenceFacade
    ) {
        $this->assetRepository = $assetRepository;
        $this->assetEntityManager = $assetEntityManager;
        $this->storeReferenceFacade = $storeReferenceFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AssetDeletedTransfer $assetDeletedTransfer
     *
     * @return void
     */
    public function deleteAsset(AssetDeletedTransfer $assetDeletedTransfer): void
    {
        $messageAttributes = $assetDeletedTransfer->getMessageAttributesOrFail();
        $assetDeletedTransfer
            ->requireAssetIdentifier();

        $storeTransfer = $this->storeReferenceFacade->getStoreByStoreReference($messageAttributes->getStoreReferenceOrFail());
        $assetTransfer = $this->assetRepository
            ->findAssetByAssetUuid((string)$assetDeletedTransfer->getAssetIdentifier());

        if (!$assetTransfer) {
            return;
        }

        $this->assetEntityManager->deleteAsset($assetTransfer);
        $this->assetEntityManager->deleteAssetStores($assetTransfer, [$storeTransfer]);
    }
}
