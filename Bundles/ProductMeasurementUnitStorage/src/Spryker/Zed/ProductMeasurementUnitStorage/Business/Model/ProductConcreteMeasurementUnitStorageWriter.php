<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business\Model;

use Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer;
use Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface;
use Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageEntityManagerInterface;
use Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface;

class ProductConcreteMeasurementUnitStorageWriter implements ProductConcreteMeasurementUnitStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface
     */
    protected $productMeasurementUnitFacade;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface
     */
    protected $productMeasurementUnitStorageRepository;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageEntityManagerInterface
     */
    protected $productMeasurementUnitStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnitStorage\Business\Model\ProductConcreteMeasurementUnitStorageReaderInterface
     */
    protected $productConcreteMeasurementUnitStorageReader;

    /**
     * @var array Keys are product measurement unit ids, values are product measurement unit codes.
     */
    protected static $productMeasurementUnitCodeBuffer;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface $productMeasurementUnitStorageRepository
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageEntityManagerInterface $productMeasurementUnitStorageEntityManager
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\Business\Model\ProductConcreteMeasurementUnitStorageReaderInterface $productConcreteMeasurementUnitStorageReader
     */
    public function __construct(
        ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade,
        ProductMeasurementUnitStorageRepositoryInterface $productMeasurementUnitStorageRepository,
        ProductMeasurementUnitStorageEntityManagerInterface $productMeasurementUnitStorageEntityManager,
        ProductConcreteMeasurementUnitStorageReaderInterface $productConcreteMeasurementUnitStorageReader
    ) {
        $this->productMeasurementUnitFacade = $productMeasurementUnitFacade;
        $this->productMeasurementUnitStorageRepository = $productMeasurementUnitStorageRepository;
        $this->productMeasurementUnitStorageEntityManager = $productMeasurementUnitStorageEntityManager;
        $this->productConcreteMeasurementUnitStorageReader = $productConcreteMeasurementUnitStorageReader;
    }

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function publish(array $productIds): void
    {
        $mappedProductConcreteMeasurementUnitStorageEntityTransfer =
            $this->findMappedProductConcreteMeasurementUnitStorageEntityTransfers($productIds);

        foreach ($productIds as $idProduct) {
            $storageEntitiesWithStore = [];

            if (isset($mappedProductConcreteMeasurementUnitStorageEntityTransfer[$idProduct])) {
                $storageEntitiesWithStore = $mappedProductConcreteMeasurementUnitStorageEntityTransfer[$idProduct];
            }

            unset($mappedProductConcreteMeasurementUnitStorageEntityTransfer[$idProduct]);

            $this->saveStorageEntityTransfer($idProduct, $storageEntitiesWithStore);
        }

        $this->deleteNotFoundStorageEntityTransfer($mappedProductConcreteMeasurementUnitStorageEntityTransfer);
    }

    /**
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer[] $storageEntityTransfers
     *
     * @return void
     */
    protected function saveStorageEntityTransfer(int $idProduct, array $storageEntityTransfers): void
    {
        $measurementsUnitData = $this->getMeasurementsUnitData($idProduct);
        foreach ($measurementsUnitData as $store => $data) {
            if (!isset($storageEntityTransfers[$store])) {
                $storageEntityTransfers[$store] = new SpyProductConcreteMeasurementUnitStorageEntityTransfer();
            }

            $storageEntityTransfers[$store]
                ->setFkProduct($idProduct)
                ->setStore($store)
                ->setData($data);

            $this->productMeasurementUnitStorageEntityManager->saveProductConcreteMeasurementUnitStorageEntity($storageEntityTransfers[$store]);
            unset($storageEntityTransfers[$store]);
        }

        $this->deleteNotFoundStorageEntityTransfer($storageEntityTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer[] $spyProductConcreteMeasurementUnitStorageEntityTransfers
     *
     * @return void
     */
    protected function deleteNotFoundStorageEntityTransfer(array $spyProductConcreteMeasurementUnitStorageEntityTransfers): void
    {
        foreach ($spyProductConcreteMeasurementUnitStorageEntityTransfers as $productConcreteMeasurementUnitStorageEntity) {
            $this->productMeasurementUnitStorageEntityManager->deleteProductConcreteMeasurementUnitStorage(
                $productConcreteMeasurementUnitStorageEntity->getIdProductConcreteMeasurementUnitStorage()
            );
        }
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer[]
     */
    protected function getMeasurementsUnitData(int $idProduct): array
    {
        return $this->productConcreteMeasurementUnitStorageReader->getProductConcreteMeasurementUnitStorageByIdProduct($idProduct);
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer[] Keys are product IDs
     */
    protected function findMappedProductConcreteMeasurementUnitStorageEntityTransfers(array $productIds): array
    {
        $productConcreteMeasurementUnitStorageEntityTransfers = $this->productMeasurementUnitStorageRepository
            ->findProductConcreteMeasurementUnitStorageEntities($productIds);

        $mappedProductConcreteMeasurementUnitStorageEntitiesTransfer = [];
        foreach ($productConcreteMeasurementUnitStorageEntityTransfers as $unitStorageEntityTransfer) {
            $mappedProductConcreteMeasurementUnitStorageEntitiesTransfer[$unitStorageEntityTransfer->getFkProduct()][$unitStorageEntityTransfer->getStore()] = $unitStorageEntityTransfer;
        }

        return $mappedProductConcreteMeasurementUnitStorageEntitiesTransfer;
    }

    /**
     * @param int $idProductMeasurementUnit
     *
     * @return string
     */
    protected function getProductMeasurementUnitCodeById(int $idProductMeasurementUnit): string
    {
        if (!static::$productMeasurementUnitCodeBuffer) {
            $this->loadProductMeasurementUnitCodes();
        }

        return static::$productMeasurementUnitCodeBuffer[$idProductMeasurementUnit];
    }

    /**
     * @return void
     */
    protected function loadProductMeasurementUnitCodes(): void
    {
        static::$productMeasurementUnitCodeBuffer = $this->productMeasurementUnitFacade->getProductMeasurementUnitCodeMap();
    }
}
