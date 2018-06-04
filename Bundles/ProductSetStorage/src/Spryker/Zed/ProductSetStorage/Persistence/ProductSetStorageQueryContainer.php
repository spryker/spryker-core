<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Persistence;

use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductAbstractSetTableMap;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetDataTableMap;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductSetStorage\Persistence\ProductSetStoragePersistenceFactory getFactory()
 */
class ProductSetStorageQueryContainer extends AbstractQueryContainer implements ProductSetStorageQueryContainerInterface
{
    const FK_PRODUCT_RESOURCE_SET = 'fkProductSet';

    /**
     * @api
     *
     * @param array $productSetIds
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetDataQuery
     */
    public function queryProductSetDataByIds(array $productSetIds)
    {
        return $this->getFactory()
            ->getProductSetQueryContainer()
            ->queryAllProductSetData()
            ->joinWithSpyLocale()
            ->joinWithSpyProductSet()
            ->joinWith('SpyProductSet.SpyProductAbstractSet')
            ->joinWith('SpyProductSet.SpyProductImageSet', Criteria::LEFT_JOIN)
            ->addJoinCondition('SpyProductImageSet', sprintf('(spy_product_image_set.fk_locale = %s or spy_product_image_set.fk_locale is null)', SpyProductSetDataTableMap::COL_FK_LOCALE))
            ->joinWith('SpyProductImageSet.SpyProductImageSetToProductImage', Criteria::LEFT_JOIN)
            ->joinWith('SpyProductImageSetToProductImage.SpyProductImage', Criteria::LEFT_JOIN)
            ->filterByFkProductSet_In($productSetIds)
            ->addJoin(
                SpyProductSetTableMap::COL_ID_PRODUCT_SET,
                SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_SET,
                Criteria::INNER_JOIN
            )
            ->where(SpyUrlTableMap::COL_FK_LOCALE . ' = ' . SpyProductSetDataTableMap::COL_FK_LOCALE)
            ->withColumn(SpyUrlTableMap::COL_URL, 'url')
            ->orderBy(SpyProductAbstractSetTableMap::COL_POSITION, Criteria::ASC)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);
    }

    /**
     * @api
     *
     * @param array $productSetIds
     *
     * @return \Orm\Zed\ProductSetStorage\Persistence\SpyProductSetStorageQuery
     */
    public function queryProductSetStorageByIds(array $productSetIds)
    {
        return $this->getFactory()
            ->createSpyProductSetStorageQuery()
            ->filterByFkProductSet_In($productSetIds);
    }

    /**
     * @api
     *
     * @param array $productImageIds
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductSetIdsByProductImageIds(array $productImageIds)
    {
        return $this->getFactory()->getProductImageQueryContainer()
            ->queryProductImageSetToProductImage()
            ->filterByFkProductImage_In($productImageIds)
            ->innerJoinSpyProductImageSet()
            ->withColumn('DISTINCT ' . SpyProductImageSetTableMap::COL_FK_RESOURCE_PRODUCT_SET, static::FK_PRODUCT_RESOURCE_SET)
            ->select([static::FK_PRODUCT_RESOURCE_SET])
            ->addAnd(SpyProductImageSetTableMap::COL_FK_RESOURCE_PRODUCT_SET, null, ModelCriteria::NOT_EQUAL);
    }

    /**
     * @api
     *
     * @param array $productImageSetToProductImageIds
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductSetIdsByProductImageSetToProductImageIds(array $productImageSetToProductImageIds)
    {
        $query = $this->getFactory()->getProductImageQueryContainer()
            ->queryProductImageSetToProductImage()
            ->filterByIdProductImageSetToProductImage_In($productImageSetToProductImageIds)
            ->innerJoinSpyProductImageSet()
            ->withColumn('DISTINCT ' . SpyProductImageSetTableMap::COL_FK_RESOURCE_PRODUCT_SET, static::FK_PRODUCT_RESOURCE_SET)
            ->select([static::FK_PRODUCT_RESOURCE_SET])
            ->addAnd(SpyProductImageSetTableMap::COL_FK_RESOURCE_PRODUCT_SET, null, ModelCriteria::NOT_EQUAL);

        return $query;
    }
}
