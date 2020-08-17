<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Storage;

use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\ProductConfigurationStorageToStorageClientInterface;
use Spryker\Client\ProductConfigurationStorage\Dependency\ProductConfigurationStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductConfigurationStorage\ProductConfigurationStorageConfig;

class ProductConfigurationStorageReader implements ProductConfigurationStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\ProductConfigurationStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\ProductConfigurationStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\ProductConfigurationStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\ProductConfigurationStorageToStorageClientInterface $storageClient
     */
    public function __construct(
        ProductConfigurationStorageToSynchronizationServiceInterface $synchronizationService,
        ProductConfigurationStorageToStorageClientInterface $storageClient
    ) {
        $this->synchronizationService = $synchronizationService;
        $this->storageClient = $storageClient;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationStorageTransfer|null
     */
    public function findProductConfigurationStorageBySku(
        string $sku
    ): ?ProductConfigurationStorageTransfer {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($sku);

        $key = $this->synchronizationService
            ->getStorageKeyBuilder(ProductConfigurationStorageConfig::PRODUCT_CONFIGURATION_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);

        return $this->storageClient->get($key);
    }
}
