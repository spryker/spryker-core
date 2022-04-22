<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Business\Creator;

use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetTransfer;
use Spryker\Zed\Asset\Business\Exception\InvalidAssetException;
use Spryker\Zed\Asset\Business\Mapper\AssetMapperInterface;
use Spryker\Zed\Asset\Dependency\Facade\AssetToStoreReferenceInterface;
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
     * @var \Spryker\Zed\Asset\Dependency\Facade\AssetToStoreReferenceInterface
     */
    private $storeReferenceFacade;

    /**
     * @param \Spryker\Zed\Asset\Persistence\AssetRepositoryInterface $assetRepository
     * @param \Spryker\Zed\Asset\Persistence\AssetEntityManagerInterface $assetEntityManager
     * @param \Spryker\Zed\Asset\Business\Mapper\AssetMapperInterface $assetMapper
     * @param \Spryker\Zed\Asset\Dependency\Facade\AssetToStoreReferenceInterface $storeReferenceFacade
     */
    public function __construct(
        AssetRepositoryInterface $assetRepository,
        AssetEntityManagerInterface $assetEntityManager,
        AssetMapperInterface $assetMapper,
        AssetToStoreReferenceInterface $storeReferenceFacade
    ) {
        $this->assetRepository = $assetRepository;
        $this->assetEntityManager = $assetEntityManager;
        $this->assetMapper = $assetMapper;
        $this->storeReferenceFacade = $storeReferenceFacade;
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

        $storeTransfer = $this->storeReferenceFacade->getStoreByStoreReference($messageAttributes->getStoreReferenceOrFail());
        $assetTransfer = $this->assetRepository
            ->findAssetByAssetUuid((string)$assetAddedTransfer->getAssetIdentifier());

        if ($assetTransfer !== null) {
            throw new InvalidAssetException('This asset already exists in DB.');
        }

        $assetTransfer = $this->assetMapper->mapAssetAddedTransferToAssetTransfer(
            $assetAddedTransfer,
            new AssetTransfer(),
        );

        return $this->assetEntityManager
            ->saveAssetWithStores($assetTransfer, [$storeTransfer]);
    }
}
