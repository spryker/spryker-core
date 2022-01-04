<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage\Storage;

use Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStorageClientInterface;
use Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStoreClientInterface;
use Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToUtilEncodingServiceInterface;
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
     * @var \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStoreClientInterface $storeClient
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductMeasurementUnitStorageToStorageClientInterface $storageClient,
        ProductMeasurementUnitStorageToStoreClientInterface $storeClient,
        ProductMeasurementUnitStorageToSynchronizationServiceInterface $synchronizationService,
        ProductMeasurementUnitStorageToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->utilEncodingService = $utilEncodingService;
        $this->storeClient = $storeClient;
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
     * @param array<int> $productConcreteIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer>
     */
    public function getProductConcreteMeasurementUnitStorageCollection(array $productConcreteIds): array
    {
        if (!$productConcreteIds) {
            return [];
        }

        $productConcreteMeasurementUnitsStorageData = $this->storageClient->getMulti($this->generateKeys($productConcreteIds));

        return $this
            ->mapProductMeasurementUnitStorageDataToProductConcreteMeasurementUnitStorageTransfers(
                $productConcreteMeasurementUnitsStorageData,
            );
    }

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<string>
     */
    protected function generateKeys(array $productConcreteIds): array
    {
        $productConcreteMeasurementUnitStorageKeys = [];
        foreach ($productConcreteIds as $idProductConcrete) {
            $productConcreteMeasurementUnitStorageKeys[] = $this->generateKey($idProductConcrete);
        }

        return $productConcreteMeasurementUnitStorageKeys;
    }

    /**
     * @param array<string> $productConcreteMeasurementUnitsStorageData
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer>
     */
    protected function mapProductMeasurementUnitStorageDataToProductConcreteMeasurementUnitStorageTransfers(
        array $productConcreteMeasurementUnitsStorageData
    ): array {
        $productConcreteMeasurementUnitStorageTransfers = [];
        foreach ($productConcreteMeasurementUnitsStorageData as $storageKey => $dataItem) {
            if (!$dataItem) {
                continue;
            }

            $productConcreteMeasurementUnitStorageData = $this->utilEncodingService->decodeJson($dataItem, true);
            if (!$productConcreteMeasurementUnitStorageData) {
                continue;
            }

            $idProductConcrete = $this->getIdProductConcrete($storageKey);
            $productConcreteMeasurementUnitStorageTransfers[$idProductConcrete] =
                $this->mapToProductConcreteMeasurementUnitStorage($productConcreteMeasurementUnitStorageData);
        }

        return $productConcreteMeasurementUnitStorageTransfers;
    }

    /**
     * @param string $storageKey
     *
     * @return int
     */
    protected function getIdProductConcrete(string $storageKey): int
    {
        $storageKeyArray = explode(':', $storageKey);

        return (int)end($storageKeyArray);
    }

    /**
     * @param array $productConcreteMeasurementUnitStorageData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer
     */
    protected function mapToProductConcreteMeasurementUnitStorage(
        array $productConcreteMeasurementUnitStorageData
    ): ProductConcreteMeasurementUnitStorageTransfer {
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
            ->setStore($this->storeClient->getCurrentStore()->getNameOrFail())
            ->setReference($idProduct);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductMeasurementUnitStorageConfig::PRODUCT_CONCRETE_MEASUREMENT_UNIT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
