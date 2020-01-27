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
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig;

class ProductConcreteMeasurementUnitStorageReader implements ProductConcreteMeasurementUnitStorageReaderInterface
{
    /**
     * @uses \Spryker\Zed\Storage\Communication\Table\StorageTable::KV_PREFIX
     */
    protected const KV_PREFIX = 'kv:';

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
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStorageClientInterface $storageClient
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        ProductMeasurementUnitStorageToStorageClientInterface $storageClient,
        Store $store,
        ProductMeasurementUnitStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->store = $store;
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
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer[]
     */
    public function getBulkProductConcreteMeasurementUnitStorage(array $productConcreteIds): array
    {
        if (!$productConcreteIds) {
            return [];
        }

        $productMeasurementUnitStorageKeys = [];
        foreach ($productConcreteIds as $productConcreteSku => $idProductConcrete) {
            $productMeasurementUnitStorageKeys[$productConcreteSku] = $this->generateKey($idProductConcrete);
        }
        $productConcreteMeasurementUnitsStorageData = $this->storageClient->getMulti($productMeasurementUnitStorageKeys);
        $productConcreteMeasurementUnitStorageTransfers = $this
            ->mapProductMeasurementUnitStorageDataToProductConcreteMeasurementUnitStorageTransfers(
                $productConcreteMeasurementUnitsStorageData
            );

        return $productConcreteMeasurementUnitStorageTransfers;
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
        foreach ($productConcreteMeasurementUnitsStorageData as $storageKey => $data) {
            if (!$data) {
                continue;
            }

            $arrayStorageKey = explode(':', $storageKey);
            $idProductConcrete = $arrayStorageKey[count($arrayStorageKey) - 1];
            $productConcreteMeasurementUnitStorageTransfers[$idProductConcrete] =
                $this->mapToProductConcreteMeasurementUnitStorage(json_decode($data, true));
        }

        return $productConcreteMeasurementUnitStorageTransfers;
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
            ->setStore($this->store->getStoreName())
            ->setReference($idProduct);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductMeasurementUnitStorageConfig::PRODUCT_CONCRETE_MEASUREMENT_UNIT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
