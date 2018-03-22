<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductConcreteMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitExchangeDetailTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnitQuery;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery;
use Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorage;
use Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorageQuery;
use Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface;

class ProductConcreteMeasurementUnitStorageWriter implements ProductConcreteMeasurementUnitStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface
     */
    protected $productMeasurementUnitFacade;

    /**
     * @var array Keys are product measurement unit ids, values are product measurement unit codes.
     */
    protected static $productMeasurementUnitCodeBuffer;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
     */
    public function __construct(ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade)
    {
        $this->productMeasurementUnitFacade = $productMeasurementUnitFacade;
    }

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function publish(array $productIds)
    {
        $productConcreteMeasurementUnitEntities = $this->getProductConcreteMeasurementUnitEntities($productIds);

        $productConcreteMeasurementUnitStorageEntities = $this->getProductConcreteMeasurementUnitStorageEntities($productIds);
        $mappedProductConcreteMeasurementUnitStorageEntities = $this->mapProductConcreteMeasurementUnitStorageEntities($productConcreteMeasurementUnitStorageEntities);

        foreach ($productConcreteMeasurementUnitEntities as $productConcreteMeasurementUnitEntity) {
            $idProduct = $productConcreteMeasurementUnitEntity->getIdProduct();

            $storageEntity = isset($mappedProductConcreteMeasurementUnitStorageEntities[$idProduct]) ?
                $mappedProductConcreteMeasurementUnitStorageEntities[$idProduct] :
                new SpyProductConcreteMeasurementUnitStorage();

            unset($mappedProductConcreteMeasurementUnitStorageEntities[$idProduct]);

            $storageEntity
                ->setFkProduct($idProduct)
                ->setData($this->getStorageEntityData($productConcreteMeasurementUnitEntity)->toArray(true))
                ->save();
        }

        array_walk_recursive(
            $mappedProductConcreteMeasurementUnitStorageEntities,
            function (SpyProductConcreteMeasurementUnitStorage $productConcreteMeasurementUnitStorageEntity) {
                $productConcreteMeasurementUnitStorageEntity->delete();
            }
        );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer
     */
    protected function getStorageEntityData(SpyProduct $productEntity)
    {
        $productMeasurementBaseUnitEntity = SpyProductMeasurementBaseUnitQuery::create()
            ->findOneByFkProductAbstract($productEntity->getFkProductAbstract());

        $productConcreteMeasurementUnitStorageTransfer = (new ProductConcreteMeasurementUnitStorageTransfer())
            ->setBaseUnit(
                (new ProductConcreteMeasurementBaseUnitTransfer())
                    ->setMeasurementUnitId($productMeasurementBaseUnitEntity->getFkProductMeasurementUnit())
            )
            ->setSalesUnits(new ArrayObject());

        foreach ($productEntity->getSpyProductMeasurementSalesUnits() as $productMeasurementSalesUnitEntity) {
            $exchangeDetails = $this->getExchangeDetails(
                $productMeasurementSalesUnitEntity->getConversion(),
                $productMeasurementSalesUnitEntity->getPrecision(),
                $this->getProductMeasurementUnitCodeById($productMeasurementSalesUnitEntity->getFkProductMeasurementUnit()),
                $this->getProductMeasurementUnitCodeById($productMeasurementBaseUnitEntity->getFkProductMeasurementUnit())
            );

            $productConcreteMeasurementUnitStorageTransfer->addSalesUnit(
                (new ProductConcreteMeasurementSalesUnitTransfer())
                    ->setMeasurementUnitId($productMeasurementSalesUnitEntity->getFkProductMeasurementUnit())
                    ->setConversion($exchangeDetails->getConversion())
                    ->setPrecision($exchangeDetails->getPrecision())
                    ->setIsDisplay((bool)$productMeasurementSalesUnitEntity->getIsDisplay())
                    ->setIsDefault((bool)$productMeasurementSalesUnitEntity->getIsDefault())
            );
        }

        return $productConcreteMeasurementUnitStorageTransfer;
    }

    /**
     * @param float|null $conversion
     * @param int|null $precision
     * @param string $fromCode
     * @param string $toCode
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitExchangeDetailTransfer
     */
    protected function getExchangeDetails($conversion, $precision, $fromCode, $toCode)
    {
        if (is_numeric($conversion) && is_int($precision)) {
            return (new ProductMeasurementUnitExchangeDetailTransfer())
                ->setConversion($conversion)
                ->setPrecision($precision);
        }

        $exchangeDetails = (new ProductMeasurementUnitExchangeDetailTransfer())
            ->setFromCode($fromCode)
            ->setToCode($toCode);

        $exchangeDetails = $this->productMeasurementUnitFacade->getExchangeDetail($exchangeDetails);
        if (is_numeric($conversion)) {
            $exchangeDetails->setConversion($conversion);
        }
        if (is_int($precision)) {
            $exchangeDetails->setPrecision($precision);
        }

        return $exchangeDetails;
    }

    /**
     * @param int[] $productIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct[]
     */
    protected function getProductConcreteMeasurementUnitEntities(array $productIds)
    {
        return SpyProductQuery::create()
            ->filterByIdProduct_In($productIds)
            ->joinWithSpyProductAbstract()
            ->leftJoinWithSpyProductMeasurementSalesUnit()
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param int[] $productIds
     *
     * @return \Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorage[]
     */
    protected function getProductConcreteMeasurementUnitStorageEntities(array $productIds)
    {
        return SpyProductConcreteMeasurementUnitStorageQuery::create()
            ->filterByFkProduct_In($productIds)
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param \Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorage[] $productConcreteMeasurementUnitStorageEntities
     *
     * @return array
     */
    protected function mapProductConcreteMeasurementUnitStorageEntities(array $productConcreteMeasurementUnitStorageEntities)
    {
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
        static::$productMeasurementUnitCodeBuffer = SpyProductMeasurementUnitQuery::create()
            ->find()
            ->toKeyValue('idProductMeasurementUnit', 'code');
    }
}
