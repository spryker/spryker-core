<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Persistence;

use Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer;
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
     * @return \Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer
     */
    public function getProductMeasurementBaseUnitEntityByIdProduct(int $idProduct): SpyProductMeasurementBaseUnitEntityTransfer
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

        $productMeasurementBaseUnitEntities = $this->buildQueryFromCriteria($query)->find();
        if (count($productMeasurementBaseUnitEntities) < 1) {
            throw new EntityNotFoundException(sprintf(static::ERROR_NO_BASE_UNIT_FOR_ID_PRODUCT, $idProduct));
        }

        return $productMeasurementBaseUnitEntities[0];
    }

    /**
     * @param int $idProductMeasurementSalesUnit
     *
     * @throws \Propel\Runtime\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer
     */
    public function getProductMeasurementSalesUnitEntity(int $idProductMeasurementSalesUnit): SpyProductMeasurementSalesUnitEntityTransfer
    {
        $query = $this->getFactory()
            ->createProductMeasurementSalesUnitQuery()
            ->filterByIdProductMeasurementSalesUnit($idProductMeasurementSalesUnit)
            ->joinWithProductMeasurementUnit();

        $productMeasurementSalesUnitEntities = $this->buildQueryFromCriteria($query)->find();
        if (count($productMeasurementSalesUnitEntities) < 1) {
            throw new EntityNotFoundException(sprintf(static::ERROR_NO_SALES_UNIT_BY_ID, $idProductMeasurementSalesUnit));
        }

        return $productMeasurementSalesUnitEntities[0];
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer[]
     */
    public function getProductMeasurementSalesUnitEntitiesByIdProduct(int $idProduct): array
    {
        $query = $this->getFactory()
            ->createProductMeasurementSalesUnitQuery()
            ->filterByFkProduct($idProduct)
            ->joinWithSpyProductMeasurementSalesUnitStore()
            ->joinWith('SpyProductMeasurementSalesUnitStore.SpyStore')
            ->joinWithProductMeasurementUnit();

        return $this->buildQueryFromCriteria($query)
            ->find();
    }

    /**
     * @param int $idProductMeasurementBaseUnit
     *
     * @throws \Propel\Runtime\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer
     */
    public function getProductMeasurementBaseUnitEntity(int $idProductMeasurementBaseUnit): SpyProductMeasurementBaseUnitEntityTransfer
    {
        $query = $this->getFactory()
            ->createProductMeasurementBaseUnitQuery()
            ->filterByIdProductMeasurementBaseUnit($idProductMeasurementBaseUnit)
            ->joinWithProductMeasurementUnit();

        $productMeasurementBaseUnitEntities = $this->buildQueryFromCriteria($query)->find();
        if (count($productMeasurementBaseUnitEntities) < 1) {
            throw new EntityNotFoundException(sprintf(static::ERROR_NO_BASE_UNIT_BY_ID, $idProductMeasurementBaseUnit));
        }

        return $productMeasurementBaseUnitEntities[0];
    }

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer[]
     */
    public function findProductMeasurementUnitEntities(array $productMeasurementUnitIds): array
    {
        if (!$productMeasurementUnitIds) {
            return [];
        }

        $query = $this->getFactory()
            ->createProductMeasurementUnitQuery()
            ->filterByIdProductMeasurementUnit_In($productMeasurementUnitIds);

        return $this->buildQueryFromCriteria($query)
            ->find();
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
