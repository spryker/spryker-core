<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStoragePersistenceFactory getFactory()
 */
class ProductStorageQueryContainer extends AbstractQueryContainer implements ProductStorageQueryContainerInterface
{
    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractByIds(array $productAbstractIds)
    {
        $query = $this->getFactory()->getProductQueryContainer()
            ->queryAllProductAbstractLocalizedAttributes()
            ->joinWithLocale()
            ->joinWithSpyProductAbstract()
            ->useSpyProductAbstractQuery()
                ->joinWithSpyProduct()
                ->joinWithSpyProductAbstractStore()
                ->useSpyProductAbstractStoreQuery()
                    ->joinWithSpyStore()
                ->endUse()
            ->endUse()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);

        $query
            ->join('SpyProductAbstract.SpyUrl')
            ->addJoinCondition('SpyUrl', 'spy_url.fk_locale = ' . SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE)
            ->withColumn(SpyUrlTableMap::COL_URL, 'url');

        return $query;
    }

    /**
     * @api
     *
     * @param array $productIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery
     */
    public function queryProductConcreteByIds(array $productIds)
    {
        $query = $this->getFactory()->getProductQueryContainer()
            ->queryAllProductLocalizedAttributes()
            ->joinWithLocale()
            ->joinWithSpyProduct()
            ->useSpyProductQuery()
                ->joinWithSpyProductAbstract()
            ->endUse()
            ->filterByFkProduct_In($productIds)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);

        $query
            ->join('SpyProductAbstract.SpyProductAbstractLocalizedAttributes')
            ->addJoinCondition('SpyProductAbstractLocalizedAttributes', 'SpyProductAbstractLocalizedAttributes.fk_locale = ' . SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE)
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_META_TITLE, 'meta_title')
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_META_KEYWORDS, 'meta_keywords')
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_META_DESCRIPTION, 'meta_description')
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_DESCRIPTION, 'abstract_description')
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_ATTRIBUTES, 'abstract_attributes');

        $query
            ->join('SpyProductAbstract.SpyUrl')
            ->addJoinCondition('SpyUrl', 'spy_url.fk_locale = ' . SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE)
            ->withColumn(SpyUrlTableMap::COL_URL, 'url');

        return $query;
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorageQuery
     */
    public function queryProductAbstractStorageByIds(array $productAbstractIds)
    {
        $query = $this
            ->getFactory()
            ->createSpyProductAbstractStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);

        return $query;
    }

    /**
     * @api
     *
     * @param array $productIds
     *
     * @return \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorageQuery
     */
    public function queryProductConcreteStorageByIds(array $productIds)
    {
        $query = $this
            ->getFactory()
            ->createSpyProductConcreteStorageQuery()
            ->filterByFkProduct_In($productIds);

        return $query;
    }

    /**
     * @api
     *
     * @param bool $isSuper
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey($isSuper = true)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAttributeKey()
            ->filterByIsSuper($isSuper);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryConcreteProduct($idProductAbstract, $idLocale)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProduct()
            ->select([
                SpyProductTableMap::COL_ID_PRODUCT,
                SpyProductTableMap::COL_ATTRIBUTES,
                SpyProductTableMap::COL_SKU,
            ])
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_ATTRIBUTES, 'localized_attributes')
            ->useSpyProductLocalizedAttributesQuery()
            ->filterByFkLocale($idLocale)
            ->endUse()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByIsActive(true);
    }

    /**
     * @api
     *
     * @param array $attributeKeys
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKeyByKey(array $attributeKeys)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAttributeKey()
            ->select(SpyProductAttributeKeyTableMap::COL_KEY)
            ->filterByIsSuper(true)
            ->filterByKey($attributeKeys, Criteria::IN);
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductIdsByProductAbstractIds(array $productAbstractIds)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProduct()
            ->select(SpyProductTableMap::COL_ID_PRODUCT)
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProducts()
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProduct();
    }
}
