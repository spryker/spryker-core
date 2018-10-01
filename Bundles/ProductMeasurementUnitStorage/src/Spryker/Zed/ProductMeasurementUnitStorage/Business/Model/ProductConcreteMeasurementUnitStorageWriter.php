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
    }

    /**
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer[] $storageEntityTransfers Keys are store names
     *
     * @return void
     */
    protected function saveStorageEntityTransfer(int $idProduct, array $storageEntityTransfers): void
    {
        $productConcreteMeasurementUnitStorageData = $this->generateProductConcreteMeasurementUnitStorageTransfersByIdProduct($idProduct);
        foreach ($productConcreteMeasurementUnitStorageData as $storeName => $storageData) {
            if (!isset($storageEntityTransfers[$storeName])) {
                $storageEntityTransfers[$storeName] = new SpyProductConcreteMeasurementUnitStorageEntityTransfer();
            }

            $storageEntityTransfers[$storeName]
                ->setFkProduct($idProduct)
                ->setStore($storeName)
                ->setData($storageData);

            $this->productMeasurementUnitStorageEntityManager->saveProductConcreteMeasurementUnitStorageEntity($storageEntityTransfers[$storeName]);
            unset($storageEntityTransfers[$storeName]);
        }

        $this->deleteNotFoundStorageEntityTransfers($storageEntityTransfers);
    }

    /**
     * @param \Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorage[] $productConcreteMeasurementUnitStorageEntityTransfers Keys are store names
     *
     * @return void
     */
    protected function deleteNotFoundStorageEntityTransfers(array $productConcreteMeasurementUnitStorageEntityTransfers): void
    {
        foreach ($productConcreteMeasurementUnitStorageEntityTransfers as $productConcreteMeasurementUnitStorageEntityTransfer) {
            $this->productMeasurementUnitStorageEntityManager->deleteProductConcreteMeasurementUnitStorage(
                $productConcreteMeasurementUnitStorageEntityTransfer->getIdProductConcreteMeasurementUnitStorage()
            );
        }
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer[] Keys are store names
     */
    protected function generateProductConcreteMeasurementUnitStorageTransfersByIdProduct(int $idProduct): array
    {
        return $this->productConcreteMeasurementUnitStorageReader->generateProductConcreteMeasurementUnitStorageTransfersByIdProduct($idProduct);
    }

    /**
     * @param int[] $productIds
     *
     * @return array First level keys are product IDs, second level keys are store names, values are storage entities
     */
    protected function findMappedProductConcreteMeasurementUnitStorageEntityTransfers(array $productIds): array
    {
        $productConcreteMeasurementUnitStorageEntityTransfers = $this->productMeasurementUnitStorageRepository
            ->findProductConcreteMeasurementUnitStorageEntities($productIds);

        $mappedProductConcreteMeasurementUnitStorageEntityTransfers = [];
        foreach ($productConcreteMeasurementUnitStorageEntityTransfers as $productConcreteMeasurementUnitStorageEntityTransfer) {
            $mappedProductConcreteMeasurementUnitStorageEntityTransfers[$productConcreteMeasurementUnitStorageEntityTransfer->getFkProduct()][$productConcreteMeasurementUnitStorageEntityTransfer->getStore()] = $productConcreteMeasurementUnitStorageEntityTransfer;
        }

        return $mappedProductConcreteMeasurementUnitStorageEntityTransfers;
    }
}
