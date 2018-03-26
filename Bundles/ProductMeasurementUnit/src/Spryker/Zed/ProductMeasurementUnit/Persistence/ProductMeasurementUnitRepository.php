<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Persistence;

use Propel\Runtime\Exception\EntityNotFoundException;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitPersistenceFactory getFactory()
 */
class ProductMeasurementUnitRepository extends AbstractRepository implements ProductMeasurementUnitRepositoryInterface
{
    const ERROR_NO_BASE_UNIT_FOR_ID_PRODUCT = 'Product measurement base unit was not found for product id "%d".';
    const ERROR_NO_BASE_UNIT_BY_ID = 'Product measurement base unit was not found by its id "%d".';
    const ERROR_NO_SALES_UNIT_BY_ID = 'Product measurement sales unit was not found by its id "%d".';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @throws \Propel\Runtime\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer
     */
    public function getProductMeasurementBaseUnitEntityByIdProduct($idProduct)
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductMeasurementSalesUnit
     *
     * @throws \Propel\Runtime\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer
     */
    public function getProductMeasurementSalesUnitEntity($idProductMeasurementSalesUnit)
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer[]
     */
    public function getProductMeasurementSalesUnitEntitiesByIdProduct($idProduct)
    {
        $query = $this->getFactory()
            ->createProductMeasurementSalesUnitQuery()
            ->filterByFkProduct($idProduct)
            ->joinWithProductMeasurementUnit();

        return $this->buildQueryFromCriteria($query)
            ->find();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductMeasurementBaseUnit
     *
     * @throws \Propel\Runtime\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer
     */
    public function getProductMeasurementBaseUnitEntity($idProductMeasurementBaseUnit)
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer[]
     */
    public function getProductMeasurementUnitEntities(array $productMeasurementUnitIds)
    {
        $query = $this->getFactory()
            ->createProductMeasurementUnitQuery()
            ->filterByIdProductMeasurementUnit_In($productMeasurementUnitIds);

        return $this->buildQueryFromCriteria($query)
            ->find();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getProductMeasurementUnitCodeMap()
    {
        return $this->getFactory()
            ->createProductMeasurementUnitQuery()
            ->find()
            ->toKeyValue('idProductMeasurementUnit', 'code');
    }
}
