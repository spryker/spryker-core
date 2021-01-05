<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryClosureTableTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStoragePersistenceFactory getFactory()
 */
class ProductCategoryStorageRepository extends AbstractRepository implements ProductCategoryStorageRepositoryInterface
{
    protected const COL_ID_CATEGORY_NODE = 'id_category_node';
    protected const COL_FK_CATEGORY_NODE_DESCENDANT = 'fk_category_node_descendant';
    protected const COL_FK_CATEGORY = 'fk_category';
    protected const COL_NAME = 'name';
    protected const COL_URL = 'url';
    protected const COL_LOCALE = 'locale';
    protected const COL_STORE = 'store';

    /**
     * @return array
     */
    public function getAllCategoriesOrderedByDescendant(): array
    {
        $categoryNodeQuery = $this->getFactory()
            ->getCategoryNodeQuery()
            ->orderBy(SpyCategoryNodeTableMap::COL_NODE_ORDER, Criteria::DESC)
            ->addJoin(
                SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE,
                SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE,
                Criteria::LEFT_JOIN
            )
            ->where(SpyUrlTableMap::COL_FK_LOCALE . ' = ' . SpyCategoryAttributeTableMap::COL_FK_LOCALE);

        $categoryNodeQuery
            ->useClosureTableQuery()
                ->orderByFkCategoryNodeDescendant(Criteria::DESC)
                ->orderByDepth(Criteria::DESC)
                ->filterByDepth(null, Criteria::NOT_EQUAL)
            ->endUse()
            ->useCategoryQuery()
                ->useSpyCategoryStoreQuery(null, Criteria::LEFT_JOIN)
                    ->joinWithSpyStore()
                ->endUse()
                ->useAttributeQuery(null, Criteria::LEFT_JOIN)
                    ->joinWithLocale()
                ->endUse()
            ->endUse();

        $categoryNodeQuery->filterByIsRoot(false);

        $categoryNodeQuery
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, static::COL_ID_CATEGORY_NODE)
            ->withColumn(SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT, static::COL_FK_CATEGORY_NODE_DESCENDANT)
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_CATEGORY, static::COL_FK_CATEGORY)
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, static::COL_NAME)
            ->withColumn(SpyUrlTableMap::COL_URL, static::COL_URL)
            ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME, static::COL_LOCALE)
            ->withColumn(SpyStoreTableMap::COL_NAME, static::COL_STORE);

        $categoryNodeQuery->select([
            static::COL_ID_CATEGORY_NODE,
            static::COL_FK_CATEGORY_NODE_DESCENDANT,
            static::COL_FK_CATEGORY,
            static::COL_NAME,
            static::COL_URL,
            static::COL_LOCALE,
            static::COL_STORE,
        ]);

        $categoryNodeQuery->setFormatter(new PropelArraySetFormatter());

        return $categoryNodeQuery->find();
    }

    /**
     * @return int[]
     */
    public function getAllCategoryNodeIds(): array
    {
        return $this->getFactory()
            ->getCategoryNodeQuery()
            ->orderBy(SpyCategoryNodeTableMap::COL_NODE_ORDER, Criteria::DESC)
            ->select([SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE])
            ->find()
            ->toArray();
    }
}
