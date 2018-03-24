<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage\Storage;

use Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStorageInterface;
use Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig;

class ProductMeasurementUnitStorageReader implements ProductMeasurementUnitStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStorageInterface $storageClient
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        ProductMeasurementUnitStorageToStorageInterface $storageClient,
        ProductMeasurementUnitStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param int $idProductMeasurementUnit
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer|null
     */
    public function findProductMeasurementUnitStorageEntity($idProductMeasurementUnit)
    {
        $key = $this->generateKey($idProductMeasurementUnit);
        $productMeasurementUnitStorageData = $this->storageClient->get($key);

        if (!$productMeasurementUnitStorageData) {
            return null;
        }

        return $this->mapToProductMeasurementUnitStorageEntity($productMeasurementUnitStorageData);
    }

    /**
     * @param array $productMeasurementUnitStorageData
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer
     */
    protected function mapToProductMeasurementUnitStorageEntity(array $productMeasurementUnitStorageData)
    {
        return (new SpyProductMeasurementUnitStorageEntityTransfer())
            ->fromArray($productMeasurementUnitStorageData, true);
    }

    /**
     * @param int $idProductMeasurementUnit
     *
     * @return string
     */
    protected function generateKey($idProductMeasurementUnit)
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($idProductMeasurementUnit);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductMeasurementUnitStorageConfig::PRODUCT_MEASUREMENT_UNIT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
