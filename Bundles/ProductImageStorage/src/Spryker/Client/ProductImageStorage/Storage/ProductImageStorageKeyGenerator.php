<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductImageStorage\Dependency\Service\ProductImageStorageToSynchronizationServiceInterface;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;

class ProductImageStorageKeyGenerator implements ProductImageStorageKeyGeneratorInterface
{
    /**
     * @var \Spryker\Client\ProductImageStorage\Dependency\Service\ProductImageStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface[]
     */
    protected static $storageKeyBuilders = [];

    /**
     * @param \Spryker\Client\ProductImageStorage\Dependency\Service\ProductImageStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(ProductImageStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param string $resourceName
     * @param int $resourceId
     * @param string $locale
     *
     * @return string
     */
    public function generateKey($resourceName, $resourceId, $locale)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setLocale($locale)
            ->setReference($resourceId);

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
}
