<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStoreClientInterface;
use Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToSynchronizationServiceInterface;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;

class PriceProductStorageKeyGenerator implements PriceProductStorageKeyGeneratorInterface
{
    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var string
     */
    protected static $currentStoreName;

    /**
     * @var SynchronizationKeyGeneratorPluginInterface[]
     */
    protected static $storageKeyBuilders = [];

    /**
     * @param \Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStoreClientInterface $storeClient
     */
    public function __construct(
        PriceProductStorageToSynchronizationServiceInterface $synchronizationService,
        PriceProductStorageToStoreClientInterface $storeClient
    ) {
        $this->synchronizationService = $synchronizationService;
        $this->storeClient = $storeClient;
    }

    /**
     * @param string $resourceName
     * @param int $resourceId
     *
     * @return string
     */
    public function generateKey($resourceName, $resourceId)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference($resourceId)
            ->setStore($this->getCurrentStoreName());

        return $this->getStorageKeyBuilder($resourceName)->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param string $resourceName
     *
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    protected function getStorageKeyBuilder(string $resourceName): SynchronizationKeyGeneratorPluginInterface
    {
        if (!isset(static::$storageKeyBuilders[$resourceName])) {
            static::$storageKeyBuilders[$resourceName] = $this->synchronizationService->getStorageKeyBuilder($resourceName);
        }

        return static::$storageKeyBuilders[$resourceName];
    }

    /**
     * @return string
     */
    protected function getCurrentStoreName(): string
    {
        if (!static::$currentStoreName) {
            static::$currentStoreName = $this->storeClient->getCurrentStore()->getName();
        }

        return static::$currentStoreName;
    }
}
