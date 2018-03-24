<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitPersistenceFactory getFactory()
 */
class ProductMeasurementUnitRepository extends AbstractRepository implements ProductMeasurementUnitRepositoryInterface
{
    /**
     * @param int $idProduct
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

        return $this->buildQueryFromCriteria($query)
            ->find()[0];
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer
     */
    public function getProductMeasurementSalesUnitEntity($idProductMeasurementSalesUnit)
    {
        $query = $this->getFactory()
            ->createProductMeasurementSalesUnitQuery()
            ->filterByIdProductMeasurementSalesUnit($idProductMeasurementSalesUnit)
            ->joinWithProductMeasurementUnit();

        return $this->buildQueryFromCriteria($query)
            ->find()[0];
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
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer[]
     */
    public function getProductMeasurementSalesUnitEntities($productIds)
    {
        $query = $this->getFactory()
            ->createProductMeasurementSalesUnitQuery()
            ->filterByFkProduct_In($productIds)
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
     * @return \Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer
     */
    public function getProductMeasurementBaseUnitEntity($idProductMeasurementBaseUnit)
    {
        $query = $this->getFactory()
            ->createProductMeasurementBaseUnitQuery()
            ->filterByIdProductMeasurementBaseUnit($idProductMeasurementBaseUnit)
            ->joinWithProductMeasurementUnit();

        return $this->buildQueryFromCriteria($query)
            ->find()[0];
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
