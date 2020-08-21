<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Storage;

use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToStorageClientInterface;
use Spryker\Client\ProductConfigurationStorage\Dependency\Service\ProductConfigurationStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductConfigurationStorage\ProductConfigurationStorageConfig;

class ProductConfigurationStorageReader implements ProductConfigurationStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\Service\ProductConfigurationStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Service\ProductConfigurationStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToStorageClientInterface $storageClient
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
    public function findProductConfigurationInstanceBySku(
        string $sku
    ): ?ProductConfigurationStorageTransfer {
        $key = $this->synchronizationService
            ->getStorageKeyBuilder(ProductConfigurationStorageConfig::PRODUCT_CONFIGURATION_RESOURCE_NAME)
            ->generateKey((new SynchronizationDataTransfer())->setReference($sku));

        $productConfigurationStorageData = $this->storageClient->get($key);

        if (empty($productConfigurationStorageData)) {
            return null;
        }

        return (new ProductConfigurationStorageTransfer())->fromArray($productConfigurationStorageData, true);
    }
}
