<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business\Model;

use Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer;
use Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Repository\ProductMeasurementUnitStorageToProductMeasurementUnitRepositoryInterface;
use Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageEntityManagerInterface;
use Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface;

class ProductConcreteMeasurementUnitStorageWriter implements ProductConcreteMeasurementUnitStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Repository\ProductMeasurementUnitStorageToProductMeasurementUnitRepositoryInterface
     */
    protected $productMeasurementUnitRepository;

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
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Repository\ProductMeasurementUnitStorageToProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface $productMeasurementUnitStorageRepository
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageEntityManagerInterface $productMeasurementUnitStorageEntityManager
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\Business\Model\ProductConcreteMeasurementUnitStorageReaderInterface $productConcreteMeasurementUnitStorageReader
     */
    public function __construct(
        ProductMeasurementUnitStorageToProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository,
        ProductMeasurementUnitStorageRepositoryInterface $productMeasurementUnitStorageRepository,
        ProductMeasurementUnitStorageEntityManagerInterface $productMeasurementUnitStorageEntityManager,
        ProductConcreteMeasurementUnitStorageReaderInterface $productConcreteMeasurementUnitStorageReader
    ) {
        $this->productMeasurementUnitRepository = $productMeasurementUnitRepository;
        $this->productMeasurementUnitStorageRepository = $productMeasurementUnitStorageRepository;
        $this->productMeasurementUnitStorageEntityManager = $productMeasurementUnitStorageEntityManager;
        $this->productConcreteMeasurementUnitStorageReader = $productConcreteMeasurementUnitStorageReader;
    }

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function publish(array $productIds)
    {
        $mappedProductConcreteMeasurementUnitStorageEntities =
            $this->getMappedProductConcreteMeasurementUnitStorageEntities($productIds);

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
    protected function selectStorageEntity(array $mappedProductConcreteMeasurementUnitStorageEntities, $idProduct)
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
    protected function saveStorageEntity($idProduct, SpyProductConcreteMeasurementUnitStorageEntityTransfer $storageEntityTransfer)
    {
        $storageEntityTransfer
            ->setFkProduct($idProduct)
            ->setData($this->getStorageEntityData($idProduct));

        $this->productMeasurementUnitStorageEntityManager->saveProductConcreteMeasurementUnitStorageEntity($storageEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer[] $mappedProductConcreteMeasurementUnitStorageEntities
     *
     * @return void
     */
    protected function deleteStorageEntities(array $mappedProductConcreteMeasurementUnitStorageEntities)
    {
        array_walk_recursive(
            $mappedProductConcreteMeasurementUnitStorageEntities,
            function (SpyProductConcreteMeasurementUnitStorageEntityTransfer $productConcreteMeasurementUnitStorageEntity) {
                $this->productMeasurementUnitStorageEntityManager->deleteProductConcreteMeasurementUnitStorage(
                    $productConcreteMeasurementUnitStorageEntity->getIdProductConcreteMeasurementUnitStorage()
                );
            }
        );
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer
     */
    protected function getStorageEntityData($idProduct)
    {
        return $this->productConcreteMeasurementUnitStorageReader->getProductConcreteMeasurementUnitStorageByIdProduct($idProduct);
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer[] Keys are product IDs
     */
    protected function getMappedProductConcreteMeasurementUnitStorageEntities(array $productIds)
    {
        $productConcreteMeasurementUnitStorageEntities = $this->productMeasurementUnitStorageRepository
            ->getProductConcreteMeasurementUnitStorageEntities($productIds);

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
    protected function getProductMeasurementUnitCodeById($idProductMeasurementUnit)
    {
        if (!static::$productMeasurementUnitCodeBuffer) {
            $this->loadProductMeasurementUnitCodes();
        }

        return static::$productMeasurementUnitCodeBuffer[$idProductMeasurementUnit];
    }

    /**
     * @return void
     */
    protected function loadProductMeasurementUnitCodes()
    {
        static::$productMeasurementUnitCodeBuffer = $this->productMeasurementUnitRepository->getProductMeasurementUnitCodeMap();
    }
}
