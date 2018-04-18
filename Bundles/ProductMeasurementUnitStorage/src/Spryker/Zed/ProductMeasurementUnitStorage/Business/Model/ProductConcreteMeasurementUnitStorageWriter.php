<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business\Model;

use Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer;
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
        $mappedProductConcreteMeasurementUnitStorageEntities =
            $this->findMappedProductConcreteMeasurementUnitStorageEntities($productIds);

        foreach ($productIds as $idProduct) {
            $storageEntity = $this->selectStorageEntity($mappedProductConcreteMeasurementUnitStorageEntities, $idProduct);

            unset($mappedProductConcreteMeasurementUnitStorageEntities[$idProduct]);

            $this->saveStorageEntity($idProduct, $storageEntity);
        }

        $this->deleteStorageEntities($mappedProductConcreteMeasurementUnitStorageEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer[] $mappedProductConcreteMeasurementUnitStorageEntities
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer
     */
    protected function selectStorageEntity(array $mappedProductConcreteMeasurementUnitStorageEntities, int $idProduct): SpyProductConcreteMeasurementUnitStorageEntityTransfer
    {
        if (isset($mappedProductConcreteMeasurementUnitStorageEntities[$idProduct])) {
            return $mappedProductConcreteMeasurementUnitStorageEntities[$idProduct];
        }

        return new SpyProductConcreteMeasurementUnitStorageEntityTransfer();
    }

    /**
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer $storageEntityTransfer
     *
     * @return void
     */
    protected function saveStorageEntity(int $idProduct, SpyProductConcreteMeasurementUnitStorageEntityTransfer $storageEntityTransfer): void
    {
        $storageEntityTransfer
            ->setFkProduct($idProduct)
            ->setData($this->getStorageEntityData($idProduct));

        $this->productMeasurementUnitStorageEntityManager->saveProductConcreteMeasurementUnitStorageEntity($storageEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer[] $productConcreteMeasurementUnitStorageEntities
     *
     * @return void
     */
    protected function deleteStorageEntities(array $productConcreteMeasurementUnitStorageEntities): void
    {
        foreach ($productConcreteMeasurementUnitStorageEntities as $productConcreteMeasurementUnitStorageEntity) {
            $this->productMeasurementUnitStorageEntityManager->deleteProductConcreteMeasurementUnitStorage(
                $productConcreteMeasurementUnitStorageEntity->getIdProductConcreteMeasurementUnitStorage()
            );
        }
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer
     */
    protected function getStorageEntityData(int $idProduct): ProductConcreteMeasurementUnitStorageTransfer
    {
        return $this->productConcreteMeasurementUnitStorageReader->getProductConcreteMeasurementUnitStorageByIdProduct($idProduct);
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer[] Keys are product IDs
     */
    protected function findMappedProductConcreteMeasurementUnitStorageEntities(array $productIds): array
    {
        $productConcreteMeasurementUnitStorageEntities = $this->productMeasurementUnitStorageRepository
            ->findProductConcreteMeasurementUnitStorageEntities($productIds);

        $mappedProductConcreteMeasurementUnitStorageEntities = [];
        foreach ($productConcreteMeasurementUnitStorageEntities as $entity) {
            $mappedProductConcreteMeasurementUnitStorageEntities[$entity->getFkProduct()] = $entity;
        }

        return $mappedProductConcreteMeasurementUnitStorageEntities;
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
