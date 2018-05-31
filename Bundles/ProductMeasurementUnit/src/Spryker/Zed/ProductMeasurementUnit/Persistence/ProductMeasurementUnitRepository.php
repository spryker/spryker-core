<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Persistence;

use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Propel\Runtime\Exception\EntityNotFoundException;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitPersistenceFactory getFactory()
 */
class ProductMeasurementUnitRepository extends AbstractRepository implements ProductMeasurementUnitRepositoryInterface
{
    protected const ERROR_NO_BASE_UNIT_FOR_ID_PRODUCT = 'Product measurement base unit was not found for product ID "%d".';
    protected const ERROR_NO_BASE_UNIT_BY_ID = 'Product measurement base unit was not found by its ID "%d".';
    protected const ERROR_NO_SALES_UNIT_BY_ID = 'Product measurement sales unit was not found by its ID "%d".';

    protected const COL_ID_PRODUCT_MEASUREMENT_UNIT = 'idProductMeasurementUnit';
    protected const COL_CODE = 'code';

    /**
     * @uses SpyProductAbstractQuery
     * @uses SpyProductQuery
     *
     * @param int $idProduct
     *
     * @throws \Propel\Runtime\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    public function getProductMeasurementBaseUnitTransferByIdProduct(int $idProduct): ProductMeasurementBaseUnitTransfer
    {
        $query = $this->getFactory()
            ->createProductMeasurementBaseUnitQuery()
            ->joinProductAbstract()
            ->useProductAbstractQuery()
                ->joinSpyProduct()
                    ->useSpyProductQuery()
                        ->filterByIdProduct($idProduct)
                    ->endUse()
            ->endUse()
            ->joinWithProductMeasurementUnit();

        $productMeasurementBaseUnitEntity = $query->findOne();
        if (!$productMeasurementBaseUnitEntity) {
            throw new EntityNotFoundException(sprintf(static::ERROR_NO_BASE_UNIT_FOR_ID_PRODUCT, $idProduct));
        }

        return $this->getFactory()
            ->createProductMeasurementUnitMapper()
            ->mapProductMeasurementBaseUnitTransfer(
                $productMeasurementBaseUnitEntity,
                new ProductMeasurementBaseUnitTransfer()
            );
    }

    /**
     * @param int $idProductMeasurementSalesUnit
     *
     * @throws \Propel\Runtime\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function getProductMeasurementSalesUnitTransfer(int $idProductMeasurementSalesUnit): ProductMeasurementSalesUnitTransfer
    {
        $query = $this->getFactory()
            ->createProductMeasurementSalesUnitQuery()
            ->filterByIdProductMeasurementSalesUnit($idProductMeasurementSalesUnit)
            ->joinWithProductMeasurementUnit()
            ->joinWithProductMeasurementBaseUnit();

        $productMeasurementSalesUnitEntity = $query->findOne();
        if (!$productMeasurementSalesUnitEntity) {
            throw new EntityNotFoundException(sprintf(static::ERROR_NO_SALES_UNIT_BY_ID, $idProductMeasurementSalesUnit));
        }

        return $this->getFactory()
            ->createProductMeasurementUnitMapper()
            ->mapProductMeasurementSalesUnitTransfer(
                $productMeasurementSalesUnitEntity,
                new ProductMeasurementSalesUnitTransfer()
            );
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getProductMeasurementSalesUnitTransfersByIdProduct(int $idProduct): array
    {
        $query = $this->getFactory()
            ->createProductMeasurementSalesUnitQuery()
            ->filterByFkProduct($idProduct)
            ->joinWithProductMeasurementBaseUnit()
            ->joinWith('ProductMeasurementUnit salesUnitMeasurementUnit')
            ->joinWith('ProductMeasurementBaseUnit.ProductMeasurementUnit baseUnitMeasurementUnit')
            ->joinWithSpyProductMeasurementSalesUnitStore()
            ->joinWith('SpyProductMeasurementSalesUnitStore.SpyStore');

        $productMeasurementSalesUnitEntityCollection = $query->find();
        $productMeasurementSalesUnitTransfers = [];
        $mapper = $this->getFactory()->createProductMeasurementUnitMapper();
        foreach ($productMeasurementSalesUnitEntityCollection as $productMeasurementSalesUnitEntity) {
            $productMeasurementSalesUnitTransfers[] = $mapper->mapProductMeasurementSalesUnitTransfer(
                $productMeasurementSalesUnitEntity,
                new ProductMeasurementSalesUnitTransfer()
            );
        }

        return $productMeasurementSalesUnitTransfers;
    }

    /**
     * @param int $idProductMeasurementBaseUnit
     *
     * @throws \Propel\Runtime\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    public function getProductMeasurementBaseUnitTransfer(int $idProductMeasurementBaseUnit): ProductMeasurementBaseUnitTransfer
    {
        $query = $this->getFactory()
            ->createProductMeasurementBaseUnitQuery()
            ->filterByIdProductMeasurementBaseUnit($idProductMeasurementBaseUnit)
            ->joinWithProductMeasurementUnit();

        $productMeasurementBaseUnitEntity = $query->findOne();
        if (!$productMeasurementBaseUnitEntity) {
            throw new EntityNotFoundException(sprintf(static::ERROR_NO_BASE_UNIT_BY_ID, $idProductMeasurementBaseUnit));
        }

        return $this->getFactory()
            ->createProductMeasurementUnitMapper()
            ->mapProductMeasurementBaseUnitTransfer(
                $productMeasurementBaseUnitEntity,
                new ProductMeasurementBaseUnitTransfer()
            );
    }

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function findProductMeasurementUnitTransfers(array $productMeasurementUnitIds): array
    {
        if (!$productMeasurementUnitIds) {
            return [];
        }

        $query = $this->getFactory()
            ->createProductMeasurementUnitQuery()
            ->filterByIdProductMeasurementUnit_In($productMeasurementUnitIds);

        $productMeasurementUnitEntityCollection = $query->find();

        $productMeasurementUnitTransfers = [];
        $mapper = $this->getFactory()->createProductMeasurementUnitMapper();
        foreach ($productMeasurementUnitEntityCollection as $productMeasurementUnitEntity) {
            $productMeasurementUnitTransfers[] = $mapper->mapProductMeasurementUnitTransfer(
                $productMeasurementUnitEntity,
                new ProductMeasurementUnitTransfer()
            );
        }

        return $productMeasurementUnitTransfers;
    }

    /**
     * @return string[]
     */
    public function getProductMeasurementUnitCodeMap(): array
    {
        return $this->getFactory()
            ->createProductMeasurementUnitQuery()
            ->find()
            ->toKeyValue(static::COL_ID_PRODUCT_MEASUREMENT_UNIT, static::COL_CODE);
    }
}
