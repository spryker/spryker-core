<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreStorage\Reader;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\StoreStorage\Dependency\Client\StoreStorageToStorageClientInterface;
use Spryker\Client\StoreStorage\Dependency\Service\StoreStorageToSynchronizationServiceInterface;
use Spryker\Shared\StoreStorage\StoreStorageConfig;

class StoreListReader
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
     * @return array<string>
     */
    public function getStoresNames(): array
    {
        $storeData = $this->storageClient->get(
            $this->generateKey(),
        );

        return $storeData['stores'] ?? [];
    }

    /**
     * @return string
     */
    protected function generateKey(): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();

        return $this->synchronizationService
            ->getStorageKeyBuilder(StoreStorageConfig::STORE_LIST_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
