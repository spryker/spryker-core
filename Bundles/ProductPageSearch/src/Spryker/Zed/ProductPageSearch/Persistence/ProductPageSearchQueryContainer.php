<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryClosureTableTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchPersistenceFactory getFactory()
 */
class ProductPageSearchQueryContainer extends AbstractQueryContainer implements ProductPageSearchQueryContainerInterface
{
    public const FK_PRODUCT_ABSTRACT = 'fkProductAbstract';
    public const FK_CATEGORY = 'fkCategory';
    public const VIRT_COLUMN_ID_CATEGORY_NODE = 'id_category_node';
    protected const COLUMN_ID_IMAGE_SET = 'id_image_set';
    protected const PRODUCT_IMAGE_SET_LIMIT = 1;

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
                ->joinWithSpyProductCategory()
                ->useSpyProductCategoryQuery()
                    ->joinWithSpyCategory()
                    ->useSpyCategoryQuery()
                        ->joinWithNode()
                    ->endUse()
                ->endUse()
                ->joinWithSpyProductAbstractStore()
                ->useSpyProductAbstractStoreQuery()
                    ->joinWithSpyStore()
                ->endUse()
            ->endUse()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->withColumn($this->getIdImageSetSubQuery(), static::COLUMN_ID_IMAGE_SET)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);

        $query
            ->joinWith('SpyProduct.SpyProductLocalizedAttributes')
            ->addJoinCondition('SpyProductLocalizedAttributes', 'SpyProductLocalizedAttributes.fk_locale = ' . SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE);

        $query
            ->join('SpyProductAbstract.SpyUrl')
            ->addJoinCondition('SpyUrl', 'spy_url.fk_locale = ' . SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE)
            ->withColumn(SpyUrlTableMap::COL_URL, 'url');

        $query
            ->rightJoinWith('SpyProduct.SpyProductSearch')
            ->addJoinCondition('SpyProductSearch', sprintf('spy_product_search.fk_locale = %s', SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE));

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @module Product
     *
     * @param int[] $productAbstractIds
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractLocalizedEntitiesByProductAbstractIdsAndStore(array $productAbstractIds, StoreTransfer $storeTransfer): SpyProductAbstractLocalizedAttributesQuery
    {
        $storeLocaleIsoCodes = array_map(function (string $localeIsoCode) {
            return $localeIsoCode;
        }, $storeTransfer->getAvailableLocaleIsoCodes());

        $query = $this->getFactory()->getProductQueryContainer()
            ->queryAllProductAbstractLocalizedAttributes()
            ->joinWithSpyProductAbstract()
            ->useSpyProductAbstractQuery()
                ->joinWithSpyProductAbstractStore()
                ->useSpyProductAbstractStoreQuery()
                    ->joinWithSpyStore()
                    ->useSpyStoreQuery()
                        ->filterByIdStore($storeTransfer->getIdStore())
                    ->endUse()
                ->endUse()
            ->endUse()
            ->joinWithLocale()
            ->useLocaleQuery()
                ->filterByLocaleName_In($storeLocaleIsoCodes)
            ->endUse()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->withColumn($this->getIdImageSetSubQuery(), static::COLUMN_ID_IMAGE_SET)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);

        $query
            ->join('SpyProductAbstract.SpyUrl')
            ->addJoinCondition('SpyUrl', SpyUrlTableMap::COL_FK_LOCALE . ' = ' . SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE)
            ->withColumn(SpyUrlTableMap::COL_URL, 'url');

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @module Product
     * @module Locale
     *
     * @param int[] $abstractProductIds
     * @param string[] $localeIsoCodes
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductConcretesByAbstractProductIdsAndLocaleIsoCodes(array $abstractProductIds, array $localeIsoCodes): SpyProductQuery
    {
        return $this->getFactory()
            ->getProductQuery()
            ->joinWithSpyProductLocalizedAttributes()
            ->useSpyProductLocalizedAttributesQuery()
                ->useLocaleQuery()
                    ->filterByLocaleName_In($localeIsoCodes)
                ->endUse()
            ->endUse()
            ->filterByFkProductAbstract_In($abstractProductIds)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @module ProductSearch
     *
     * @param int[] $productConcreteIds
     * @param string[] $localeIsoCodes
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchQuery
     */
    public function queryProductSearchByProductConcreteIdsAndLocaleIsoCodes(array $productConcreteIds, array $localeIsoCodes): SpyProductSearchQuery
    {
        return $this->getFactory()
            ->getProductSearchQuery()
            ->useSpyLocaleQuery()
                ->filterByLocaleName_In($localeIsoCodes)
            ->endUse()
            ->filterByFkProduct_In($productConcreteIds)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);
    }

    /**
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractWithLocalizedAttributesByIds(array $productAbstractIds): SpyProductAbstractLocalizedAttributesQuery
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryAllProductAbstractLocalizedAttributes()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearchQuery
     */
    public function queryProductAbstractSearchPageByIds(array $productAbstractIds)
    {
        return $this->getFactory()
            ->createProductAbstractPageSearch()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * @api
     *
     * @param array $productIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductAbstractIdsByProductIds(array $productIds)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProduct()
            ->select(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->filterByIdProduct_In($productIds);
    }

    /**
     * @api
     *
     * @param array $priceTypeIds
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryAllProductAbstractIdsByPriceTypeIds(array $priceTypeIds)
    {
        return $this->getFactory()
            ->getPriceQueryContainer()
            ->queryPriceProduct()
            ->select([SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->addAnd(SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT, null, Criteria::NOT_EQUAL)
            ->filterByFkPriceType_In($priceTypeIds);
    }

    /**
     * @api
     *
     * @param array $priceProductIds
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryAllProductAbstractIdsByPriceProductIds(array $priceProductIds)
    {
        return $this->getFactory()
            ->getPriceQueryContainer()
            ->queryPriceProduct()
            ->select([SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->addAnd(SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT, null, Criteria::NOT_EQUAL)
            ->filterByIdPriceProduct_In($priceProductIds);
    }

    /**
     * @api
     *
     * @param array $productImageSetToProductImageIds
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductAbstractIdsByProductImageSetToProductImageIds(array $productImageSetToProductImageIds)
    {
        $query = $this->getFactory()->getProductImageQueryContainer()
            ->queryProductImageSetToProductImage()
            ->filterByIdProductImageSetToProductImage_In($productImageSetToProductImageIds)
            ->innerJoinSpyProductImageSet()
            ->withColumn('DISTINCT ' . SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT, static::FK_PRODUCT_ABSTRACT)
            ->select([static::FK_PRODUCT_ABSTRACT])
            ->addAnd(SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT, null, ModelCriteria::NOT_EQUAL);

        return $query;
    }

    /**
     * @api
     *
     * @param array $productImageIds
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductAbstractIdsByProductImageIds(array $productImageIds)
    {
        return $this->getFactory()->getProductImageQueryContainer()
            ->queryProductImageSetToProductImage()
            ->filterByFkProductImage_In($productImageIds)
            ->innerJoinSpyProductImageSet()
            ->withColumn('DISTINCT ' . SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT, static::FK_PRODUCT_ABSTRACT)
            ->select([static::FK_PRODUCT_ABSTRACT])
            ->addAnd(SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT, null, ModelCriteria::NOT_EQUAL);
    }

    /**
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryAllCategoryIdsByNodeId($idNode)
    {
        return $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryClosureTableByNodeId($idNode)
            ->joinDescendantNode()
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_CATEGORY, static::FK_CATEGORY)
            ->select([static::FK_CATEGORY]);
    }

    /**
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductAbstractIdsByCategoryIds(array $categoryIds)
    {
        return $this->getFactory()
            ->getProductCategoryQueryContainer()
            ->queryProductCategoryMappings()
            ->filterByFkCategory_In($categoryIds)
            ->select(SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryCategoryAttributesByLocale(LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->getCategoryAttributeQuery()
            ->queryAllCategoryAttributes()
            ->filterByFkLocale($localeTransfer->getIdLocale());
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryAllProductImageSetsByProductAbstractIds(array $productAbstractIds)
    {
        /** @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery $productImageSetQuery */
        $productImageSetQuery = $this->getFactory()
            ->getProductImageQueryContainer()
            ->queryProductImageSet()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->joinWithSpyProductImageSetToProductImage()
            ->joinWith('SpyProductImageSetToProductImage.SpyProductImage');

        $productImageSetQuery = $this->sortProductImageSetToProductImageQuery($productImageSetQuery);

        return $productImageSetQuery;
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoriesByProductAbstractIds(array $productAbstractIds)
    {
        return $this->getFactory()->getProductCategoryQueryContainer()
            ->queryProductCategoryMappings()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->useSpyCategoryQuery()
                ->useNodeQuery()
                    ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, static::VIRT_COLUMN_ID_CATEGORY_NODE)
                ->endUse()
            ->endUse();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryAllCategoriesWithAttributesAndOrderByDescendant()
    {
        $nodeQuery = $this->getFactory()->getCategoryQueryContainer()->queryAllCategoryNodes();

        $nodeQuery
            ->useClosureTableQuery()
                ->orderByFkCategoryNodeDescendant(Criteria::DESC)
                ->orderByDepth(Criteria::DESC)
                ->filterByDepth(null, Criteria::NOT_EQUAL)
            ->endUse()
            ->useCategoryQuery()
                ->useAttributeQuery()
                ->endUse()
            ->endUse();

        $nodeQuery
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_CATEGORY, 'fk_category')
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, 'id_category_node')
            ->withColumn(SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT, 'fk_category_node_descendant')
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name')
            ->withColumn(SpyCategoryTableMap::COL_CATEGORY_KEY, 'category_key')
            ->withColumn(SpyCategoryAttributeTableMap::COL_FK_LOCALE, 'fk_locale');

        $nodeQuery->setFormatter(new PropelArraySetFormatter());

        return $nodeQuery;
    }

    /**
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodeFullPath(int $idNode): SpyCategoryNodeQuery
    {
        return $this->getFactory()
            ->getCategoryNodeQueryContainer()
            ->useClosureTableQuery()
                ->orderByFkCategoryNodeDescendant(Criteria::DESC)
                ->orderByDepth(Criteria::DESC)
                ->filterByFkCategoryNodeDescendant($idNode)
            ->endUse()
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, static::VIRT_COLUMN_ID_CATEGORY_NODE)
            ->setFormatter(new PropelArraySetFormatter());
    }

    /**
     * @return string
     */
    protected function getIdImageSetSubQuery(): string
    {
        $idImageSetSubQuery = $this->getFactory()
            ->getProductImageQueryContainer()
            ->queryProductImageSet()
            ->addSelectColumn(SpyProductImageSetTableMap::COL_ID_PRODUCT_IMAGE_SET)
            ->where(sprintf(
                '(%s = %s AND (%s = %s OR %s IS NULL)) ',
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductImageSetTableMap::COL_FK_LOCALE,
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE,
                SpyProductImageSetTableMap::COL_FK_LOCALE
            ))
            ->limit(static::PRODUCT_IMAGE_SET_LIMIT);

        $params = [];

        return sprintf('(%s)', $idImageSetSubQuery->createSelectSql($params));
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery $productImageSetToProductImageQuery
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    protected function sortProductImageSetToProductImageQuery(
        SpyProductImageSetQuery $productImageSetToProductImageQuery
    ): SpyProductImageSetQuery {
        $productImageSetToProductImageQuery->useSpyProductImageSetToProductImageQuery()
                ->orderBySortOrder()
                ->orderByIdProductImageSetToProductImage()
            ->endUse();

        return $productImageSetToProductImageQuery;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryAllProductCategories(array $productAbstractIds): SpyProductCategoryQuery
    {
        $query = $this->getFactory()->getProductCategoryQueryContainer()
            ->queryProductCategoryMappings()
            ->joinWithSpyCategory()
                ->useSpyCategoryQuery()
                    ->joinWithNode()
                ->endUse()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);

        return $query;
    }
}
