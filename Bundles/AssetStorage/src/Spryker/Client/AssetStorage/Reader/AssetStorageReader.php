<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AssetStorage\Reader;

use Generated\Shared\Transfer\AssetStorageCollectionTransfer;
use Generated\Shared\Transfer\AssetStorageCriteriaTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\AssetStorage\Dependency\Client\AssetStorageToStorageClientInterface;
use Spryker\Client\AssetStorage\Dependency\Service\AssetStorageToSynchronizationServiceInterface;
use Spryker\Client\AssetStorage\Mapper\AssetStorageMapperInterface;
use Spryker\Shared\AssetStorage\AssetStorageConfig;

class AssetStorageReader implements AssetStorageReaderInterface
{
    /**
     * @var string
     */
    protected const ASSETS_STORAGE_KEY = 'assets';

    /**
     * @var \Spryker\Client\AssetStorage\Dependency\Client\AssetStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\AssetStorage\Dependency\Service\AssetStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\AssetStorage\Mapper\AssetStorageMapperInterface
     */
    protected $assetStorageMapper;

    /**
     * @param \Spryker\Client\AssetStorage\Dependency\Client\AssetStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\AssetStorage\Dependency\Service\AssetStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\AssetStorage\Mapper\AssetStorageMapperInterface $assetStorageMapper
     */
    public function __construct(
        AssetStorageToStorageClientInterface $storageClient,
        AssetStorageToSynchronizationServiceInterface $synchronizationService,
        AssetStorageMapperInterface $assetStorageMapper
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->assetStorageMapper = $assetStorageMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\AssetStorageCriteriaTransfer $assetStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AssetStorageCollectionTransfer
     */
    public function getAssetStorageCollection(
        AssetStorageCriteriaTransfer $assetStorageCriteriaTransfer
    ): AssetStorageCollectionTransfer {
        $assetStorageKey = $this->generateKey(
            $assetStorageCriteriaTransfer->getAssetSlotOrFail(),
            $assetStorageCriteriaTransfer->getStoreNameOrFail(),
        );
        $assetStorageTransferData = $this->storageClient->get($assetStorageKey);

        if (!$assetStorageTransferData || empty($assetStorageTransferData[static::ASSETS_STORAGE_KEY])) {
            return new AssetStorageCollectionTransfer();
        }

        return $this->assetStorageMapper->mapAssetStorageDataToAssetStorageTransfer($assetStorageTransferData[static::ASSETS_STORAGE_KEY]);
    }

    /**
     * @param string $assetSlot
     * @param string $storeName
     *
     * @return string
     */
    protected function generateKey(string $assetSlot, string $storeName): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($assetSlot)
            ->setStore($storeName);

        return $this->synchronizationService
            ->getStorageKeyBuilder(AssetStorageConfig::ASSET_SLOT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
