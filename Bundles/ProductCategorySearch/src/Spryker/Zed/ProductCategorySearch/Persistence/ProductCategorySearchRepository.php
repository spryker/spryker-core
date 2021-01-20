<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategorySearch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryClosureTableTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
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
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode[]
     */
    public function getCategoryNodesByLocale(LocaleTransfer $localeTransfer): array
    {
        return $this->getFactory()
            ->getCategoryNodePropelQuery()
            ->useCategoryQuery()
                ->useAttributeQuery()
                    ->filterByFkLocale($localeTransfer->getIdLocale())
                ->endUse()
            ->endUse()
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, static::COLUMN_ID_CATEGORY_NODE)
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, static::COLUMN_CATEGORY_NAME)
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
                ->useAttributeQuery()
                ->endUse()
            ->endUse();

        $categoryNodeQuery
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_CATEGORY, static::COLUMN_FK_CATEGORY)
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, static::COLUMN_ID_CATEGORY_NODE)
            ->withColumn(SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT, static::COLUMN_FK_CATEGORY_NODE_DESCENDANT)
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, static::COLUMN_NAME)
            ->withColumn(SpyCategoryTableMap::COL_CATEGORY_KEY, static::COLUMN_CATEGORY_KEY)
            ->withColumn(SpyCategoryAttributeTableMap::COL_FK_LOCALE, static::COLUMN_FK_LOCALE);

        $categoryNodeQuery->setFormatter(new PropelArraySetFormatter());

        return $categoryNodeQuery->find();
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttribute[]
     */
    public function getCategoryAttributesByLocale(LocaleTransfer $localeTransfer): array
    {
        return $this->getFactory()
            ->getCategoryAttributePropelQuery()
            ->filterByFkLocale($localeTransfer->getIdLocale())
            ->useCategoryQuery()
                ->filterByIsSearchable(true)
            ->endUse()
            ->find()
            ->getData();
    }
}
