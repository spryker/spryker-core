<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage\Storage;

use Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStorageClientInterface;
use Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig;

class ProductConcreteMeasurementUnitStorageReader implements ProductConcreteMeasurementUnitStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        ProductMeasurementUnitStorageToStorageClientInterface $storageClient,
        ProductMeasurementUnitStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer|null
     */
    public function findProductConcreteMeasurementUnitStorage(int $idProduct): ?ProductConcreteMeasurementUnitStorageTransfer
    {
        $key = $this->generateKey($idProduct);
        $productConcreteMeasurementUnitStorageData = $this->storageClient->get($key);

        if (!$productConcreteMeasurementUnitStorageData) {
            return null;
        }

        return $this->mapToProductConcreteMeasurementUnitStorage($productConcreteMeasurementUnitStorageData);
    }

    /**
     * @param array $productConcreteMeasurementUnitStorageData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer
     */
    protected function mapToProductConcreteMeasurementUnitStorage(array $productConcreteMeasurementUnitStorageData): ProductConcreteMeasurementUnitStorageTransfer
    {
        return (new ProductConcreteMeasurementUnitStorageTransfer())
            ->fromArray($productConcreteMeasurementUnitStorageData, true);
    }

    /**
     * @param int $idProduct
     *
     * @return string
     */
    protected function generateKey(int $idProduct): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($idProduct);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductMeasurementUnitStorageConfig::PRODUCT_CONCRETE_MEASUREMENT_UNIT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
