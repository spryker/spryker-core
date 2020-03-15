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
use Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToUtilEncodingServiceInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig;

class ProductConcreteMeasurementUnitStorageReader implements ProductConcreteMeasurementUnitStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

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
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductMeasurementUnitStorageToStorageClientInterface $storageClient,
        Store $store,
        ProductMeasurementUnitStorageToSynchronizationServiceInterface $synchronizationService,
        ProductMeasurementUnitStorageToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->storageClient = $storageClient;
        $this->store = $store;
        $this->synchronizationService = $synchronizationService;
        $this->utilEncodingService = $utilEncodingService;
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
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer[]
     */
    public function getProductConcreteMeasurementUnitStorageCollection(array $productConcreteIds): array
    {
        if (!$productConcreteIds) {
            return [];
        }

        $productConcreteMeasurementUnitsStorageData = $this->storageClient->getMulti($this->generateKeys($productConcreteIds));

        return $this
            ->mapProductMeasurementUnitStorageDataToProductConcreteMeasurementUnitStorageTransfers(
                $productConcreteMeasurementUnitsStorageData
            );
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return string[]
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
     * @param string[] $productConcreteMeasurementUnitsStorageData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer[]
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
            ->setStore($this->store->getStoreName())
            ->setReference($idProduct);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductMeasurementUnitStorageConfig::PRODUCT_CONCRETE_MEASUREMENT_UNIT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
