<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage\Storage;

use Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStorageClientInterface;
use Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToUtilEncodingServiceInterface;
use Spryker\Shared\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig;

class ProductMeasurementUnitStorageReader implements ProductMeasurementUnitStorageReaderInterface
{
    protected const KEY_ID = 'id';

    /**
     * @var \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductMeasurementUnitStorageToStorageClientInterface $storageClient,
        ProductMeasurementUnitStorageToSynchronizationServiceInterface $synchronizationService,
        ProductMeasurementUnitStorageToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param int $idProductMeasurementUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer|null
     */
    public function findProductMeasurementUnitStorage(int $idProductMeasurementUnit): ?ProductMeasurementUnitStorageTransfer
    {
        $key = $this->generateKey($idProductMeasurementUnit);
        $productMeasurementUnitStorageData = $this->storageClient->get($key);

        if (!$productMeasurementUnitStorageData) {
            return null;
        }

        return $this->mapToProductMeasurementUnitStorage($productMeasurementUnitStorageData);
    }

    /**
     * @param string $mappingType
     * @param string[] $identifiers
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer[]
     */
    public function getProductMeasurementUnitsByMapping(string $mappingType, array $identifiers): array
    {
        $storageKeys = $this->generateMappingStorageKeys($mappingType, $identifiers);
        $mappings = $this->storageClient->getMulti($storageKeys);
        $productMeasurementUnitIds = [];
        foreach ($mappings as $mapping) {
            $decodedMapping = $this->utilEncodingService->decodeJson($mapping, true);
            if (!$decodedMapping) {
                continue;
            }

            $productMeasurementUnitIds[] = $decodedMapping[static::KEY_ID];
        }

        if (!$productMeasurementUnitIds) {
            return [];
        }

        return $this->getProductMeasurementUnitStorageCollection($productMeasurementUnitIds);
    }

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer[]
     */
    public function getProductMeasurementUnitStorageCollection(array $productMeasurementUnitIds): array
    {
        if (!$productMeasurementUnitIds) {
            return [];
        }

        return $this->mapProductMeasurementUnitStorageDataToProductMeasurementUnitStorageTransfers(
            $this->storageClient->getMulti(
                $this->generateKeys($productMeasurementUnitIds)
            )
        );
    }

    /**
     * @param array $productMeasurementUnitIds
     *
     * @return array
     */
    protected function generateKeys(array $productMeasurementUnitIds): array
    {
        $productMeasurementUnitStorageKeys = [];
        foreach ($productMeasurementUnitIds as $idProductMeasurementUnit) {
            $productMeasurementUnitStorageKeys[] = $this->generateKey($idProductMeasurementUnit);
        }

        return $productMeasurementUnitStorageKeys;
    }

    /**
     * @param string[] $productMeasurementUnitsStorageData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer[]
     */
    protected function mapProductMeasurementUnitStorageDataToProductMeasurementUnitStorageTransfers(
        array $productMeasurementUnitsStorageData
    ): array {
        $productConcreteMeasurementUnitStorageTransfers = [];
        foreach ($productMeasurementUnitsStorageData as $dataItem) {
            if (!$dataItem) {
                continue;
            }

            $productMeasurementUnitStorageData = $this->utilEncodingService->decodeJson($dataItem, true);
            if (!$productMeasurementUnitStorageData) {
                continue;
            }
            $productConcreteMeasurementUnitStorageTransfers[] = $this->mapToProductMeasurementUnitStorage($productMeasurementUnitStorageData);
        }

        return $productConcreteMeasurementUnitStorageTransfers;
    }

    /**
     * @param string $mappingType
     * @param string[] $identifiers
     *
     * @return string[]
     */
    protected function generateMappingStorageKeys(string $mappingType, array $identifiers): array
    {
        $mappingKeys = [];
        foreach ($identifiers as $identifier) {
            $mappingKeys[] = $this->generateKey(sprintf('%s:%s', $mappingType, $identifier));
        }

        return $mappingKeys;
    }

    /**
     * @param array $productMeasurementUnitStorageData
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer
     */
    protected function mapToProductMeasurementUnitStorage(array $productMeasurementUnitStorageData): ProductMeasurementUnitStorageTransfer
    {
        return (new ProductMeasurementUnitStorageTransfer())
            ->fromArray($productMeasurementUnitStorageData, true);
    }

    /**
     * @param int $idProductMeasurementUnit
     *
     * @return string
     */
    protected function generateKey(int $idProductMeasurementUnit): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($idProductMeasurementUnit);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductMeasurementUnitStorageConfig::PRODUCT_MEASUREMENT_UNIT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
