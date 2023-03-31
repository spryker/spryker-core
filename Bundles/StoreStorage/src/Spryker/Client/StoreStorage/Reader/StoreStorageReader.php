<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreStorage\Reader;

use Generated\Shared\Transfer\StoreStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\StoreStorage\Dependency\Client\StoreStorageToStorageClientInterface;
use Spryker\Client\StoreStorage\Dependency\Service\StoreStorageToSynchronizationServiceInterface;
use Spryker\Shared\StoreStorage\StoreStorageConfig;

class StoreStorageReader implements StoreStorageReaderInterface
{
    /**
     * @var \Spryker\Client\StoreStorage\Dependency\Service\StoreStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\StoreStorage\Dependency\Client\StoreStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @param \Spryker\Client\StoreStorage\Dependency\Service\StoreStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\StoreStorage\Dependency\Client\StoreStorageToStorageClientInterface $storageClient
     */
    public function __construct(
        StoreStorageToSynchronizationServiceInterface $synchronizationService,
        StoreStorageToStorageClientInterface $storageClient
    ) {
        $this->synchronizationService = $synchronizationService;
        $this->storageClient = $storageClient;
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\StoreStorageTransfer|null
     */
    public function findStoreByName(string $name): ?StoreStorageTransfer
    {
        $storeKey = $this->generateKey($name);
        $storeData = $this->storageClient->get($storeKey);

        if (!$storeData) {
            return null;
        }

        return (new StoreStorageTransfer())->fromArray($storeData, true);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function generateKey(string $name): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference($name);

        return $this->synchronizationService
            ->getStorageKeyBuilder(StoreStorageConfig::STORE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
