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
use Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationStorageMapperInterface;
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
     * @var \Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationStorageMapperInterface
     */
    protected $productConfigurationStorageMapper;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Service\ProductConfigurationStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationStorageMapperInterface $productConfigurationStorageMapper
     */
    public function __construct(
        ProductConfigurationStorageToSynchronizationServiceInterface $synchronizationService,
        ProductConfigurationStorageToStorageClientInterface $storageClient,
        ProductConfigurationStorageMapperInterface $productConfigurationStorageMapper
    ) {
        $this->synchronizationService = $synchronizationService;
        $this->storageClient = $storageClient;
        $this->productConfigurationStorageMapper = $productConfigurationStorageMapper;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationStorageTransfer|null
     */
    public function findProductConfigurationStorageBySku(
        string $sku
    ): ?ProductConfigurationStorageTransfer {
        $productConfigurationStorageData = $this->storageClient->get(
            $this->generateKey($sku)
        );

        if (!$productConfigurationStorageData) {
            return null;
        }

        return $this->productConfigurationStorageMapper->mapProductConfigurationStorageDataToProductConfigurationStorageTransfer(
            $productConfigurationStorageData,
            new ProductConfigurationStorageTransfer()
        );
    }

    /**
     * @param array<string> $skus
     *
     * @return array<\Generated\Shared\Transfer\ProductConfigurationStorageTransfer>
     */
    public function findProductConfigurationStoragesBySkus(array $skus): array
    {
        $storageKeys = $this->generateStorageKeys($skus);
        $productConfigurationStoragesData = $this->storageClient->getMulti($storageKeys);

        return $this->productConfigurationStorageMapper
            ->mapProductConfigurationStoragesDataToProductConfigurationStorageTransfers($productConfigurationStoragesData);
    }

    /**
     * @param array<string> $skus
     *
     * @return array<string>
     */
    protected function generateStorageKeys(array $skus): array
    {
        $storageKeys = [];

        foreach ($skus as $sku) {
            $storageKeys[] = $this->generateKey($sku);
        }

        return $storageKeys;
    }

    /**
     * @param string $reference
     *
     * @return string
     */
    protected function generateKey(string $reference): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($reference);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductConfigurationStorageConfig::PRODUCT_CONFIGURATION_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
