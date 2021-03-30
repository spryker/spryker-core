<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategorySearch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryClosureTableTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;

/**
 * @method \Spryker\Zed\ProductCategorySearch\Persistence\ProductCategorySearchPersistenceFactory getFactory()
 */
class ProductCategorySearchRepository extends AbstractRepository implements ProductCategorySearchRepositoryInterface
{
    protected const COLUMN_ID_CATEGORY_NODE = 'id_category_node';
    protected const COLUMN_CATEGORY_NAME = 'category_name';
    protected const COLUMN_FK_CATEGORY = 'fk_category';
    protected const COLUMN_FK_CATEGORY_NODE_DESCENDANT = 'fk_category_node_descendant';
    protected const COLUMN_NAME = 'name';
    protected const COLUMN_CATEGORY_KEY = 'category_key';
    protected const COLUMN_FK_LOCALE = 'fk_locale';
    protected const COLUMN_STORE_NAME = 'store_name';

    /**
     * @module Category
     * @module Store
     *
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategory[][]
     */
    public function getMappedProductCategoriesByIdProductAbstractAndStore(array $productAbstractIds): array
    {
        /** @var \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery $productCategoryQuery */
        $productCategoryQuery = $this->getFactory()
            ->getProductCategoryPropelQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->useSpyCategoryQuery()
                ->useNodeQuery()
                    ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, static::COLUMN_ID_CATEGORY_NODE)
                ->endUse()
            ->endUse();

        $productCategoryQuery
            ->useSpyCategoryQuery(null, Criteria::LEFT_JOIN)
                ->useSpyCategoryStoreQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinSpyStore()
                ->endUse()
            ->endUse();

        return $this->getFactory()
            ->createProductCategoryMapper()
            ->mapProductCategoryEntitiesByIdProductAbstractAndStore($productCategoryQuery->find(), []);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int[]
     */
    public function getCategoryNodeIdsByLocaleAndStore(LocaleTransfer $localeTransfer, StoreTransfer $storeTransfer): array
    {
        return $this->getFactory()
            ->getCategoryNodePropelQuery()
            ->useCategoryQuery()
                ->useAttributeQuery()
                    ->filterByFkLocale($localeTransfer->getIdLocale())
                ->endUse()
                ->useSpyCategoryStoreQuery()
                    ->useSpyStoreQuery()
                        ->filterByName($storeTransfer->getNameOrFail())
                    ->endUse()
                ->endUse()
            ->endUse()
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, static::COLUMN_ID_CATEGORY_NODE)
            ->select([
                static::COLUMN_ID_CATEGORY_NODE,
            ])
            ->find()
            ->getData();
    }

    /**
     * @return array
     */
    public function getAllCategoriesWithAttributesAndOrderByDescendant(): array
    {
        $categoryNodeQuery = $this->getFactory()
            ->getCategoryNodePropelQuery()
            ->orderBy(SpyCategoryNodeTableMap::COL_NODE_ORDER, Criteria::DESC)
            ->useClosureTableQuery()
                ->orderByFkCategoryNodeDescendant(Criteria::DESC)
                ->orderByDepth(Criteria::DESC)
                ->filterByDepth(null, Criteria::NOT_EQUAL)
            ->endUse()
            ->useCategoryQuery()
                ->leftJoinWithAttribute()
                ->useSpyCategoryStoreQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinWithSpyStore()
                ->endUse()
            ->endUse();

        $categoryNodeQuery
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_CATEGORY, static::COLUMN_FK_CATEGORY)
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, static::COLUMN_ID_CATEGORY_NODE)
            ->withColumn(SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT, static::COLUMN_FK_CATEGORY_NODE_DESCENDANT)
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, static::COLUMN_NAME)
            ->withColumn(SpyCategoryTableMap::COL_CATEGORY_KEY, static::COLUMN_CATEGORY_KEY)
            ->withColumn(SpyCategoryAttributeTableMap::COL_FK_LOCALE, static::COLUMN_FK_LOCALE)
            ->withColumn(SpyStoreTableMap::COL_NAME, static::COLUMN_STORE_NAME);

        /** @var array $categoryNodes */
        $categoryNodes = $categoryNodeQuery
            ->setFormatter(new PropelArraySetFormatter())
            ->find();

        return $categoryNodes;
    }

    /**
     * @param int[] $categoryIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getCategoryAttributesByLocale(array $categoryIds, LocaleTransfer $localeTransfer): array
    {
        if ($categoryIds === []) {
            return [];
        }

        return $this->getFactory()
            ->getCategoryAttributePropelQuery()
            ->filterByFkCategory_In($categoryIds)
            ->filterByFkLocale($localeTransfer->getIdLocale())
            ->useCategoryQuery()
                ->filterByIsSearchable(true)
            ->endUse()
            ->withColumn(SpyCategoryAttributeTableMap::COL_FK_CATEGORY, static::COLUMN_FK_CATEGORY)
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, static::COLUMN_CATEGORY_NAME)
            ->select([
                static::COLUMN_FK_CATEGORY,
                static::COLUMN_CATEGORY_NAME,
            ])
            ->find()
            ->getData();
    }
}
