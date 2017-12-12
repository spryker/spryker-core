<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceStorage\Persistence;

use Orm\Zed\Price\Persistence\Map\SpyPriceProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\PriceStorage\Persistence\PriceStoragePersistenceFactory getFactory()
 */
class PriceStorageQueryContainer extends AbstractQueryContainer implements PriceStorageQueryContainerInterface
{

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProductAbstractByIds(array $productAbstractIds)
    {
        return $this->getFactory()
            ->getPriceQueryContainer()
            ->queryAllPriceProduct()
            ->joinWithPriceType()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\PriceStorage\Persistence\SpyPriceAbstractStorageQuery
     */
    public function queryPriceAbstractStorageByPriceAbstractIds(array $productAbstractIds)
    {
        return $this->getFactory()
            ->createSpyPriceAbstractStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * @api
     *
     * @param array $priceTypeIds
     *
     * @return \Orm\Zed\PriceStorage\Persistence\SpyPriceAbstractStorageQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryAllProductAbstractIdsByPriceTypeIds(array $priceTypeIds)
    {
        return $this->getFactory()
            ->getPriceQueryContainer()
            ->queryAllPriceProduct()
            ->select([SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->addAnd(SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT, null, Criteria::NOT_EQUAL)
            ->filterByFkPriceType_In($priceTypeIds);
    }

    /**
     * @api
     *
     * @param array $priceTypeIds
     *
     * @return \Orm\Zed\PriceStorage\Persistence\SpyPriceAbstractStorageQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryAllProductIdsByPriceTypeIds(array $priceTypeIds)
    {
        return $this->getFactory()
            ->getPriceQueryContainer()
            ->queryAllPriceProduct()
            ->select([SpyPriceProductTableMap::COL_FK_PRODUCT])
            ->addAnd(SpyPriceProductTableMap::COL_FK_PRODUCT, null, Criteria::NOT_EQUAL)
            ->filterByFkPriceType_In($priceTypeIds);
    }

    /**
     * @api
     *
     * @param array $productConcreteIds
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProductConcreteByIds(array $productConcreteIds)
    {
        return $this->getFactory()
            ->getPriceQueryContainer()
            ->queryAllPriceProduct()
            ->joinWithPriceType()
            ->filterByFkProduct_In($productConcreteIds)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);
    }

    /**
     * @api
     *
     * @param array $productConcreteIds
     *
     * @return \Orm\Zed\PriceStorage\Persistence\SpyPriceConcreteStorageQuery
     */
    public function queryPriceConcreteStorageByProductIds(array $productConcreteIds)
    {
        return $this->getFactory()
            ->createSpyPriceConcreteStorageQuery()
            ->filterByFkProduct_In($productConcreteIds);
    }

}
