<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Category\Persistence\CategoryPersistenceFactory getFactory()
 */
class CategoryRepository extends AbstractRepository implements CategoryRepositoryInterface
{
    protected const COL_CATEGORY_NAME = 'name';

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getAllCategoryCollection(LocaleTransfer $localeTransfer): CategoryCollectionTransfer
    {
        $spyCategories = SpyCategoryQuery::create()
            ->joinWithAttribute()
            ->leftJoinNode()
            ->addAnd(
                SpyCategoryAttributeTableMap::COL_FK_LOCALE,
                $localeTransfer->getIdLocale(),
                Criteria::EQUAL
            )
            ->find();

        return $this->getFactory()
            ->createCategoryMapper()
            ->mapCategoryCollection($spyCategories, new CategoryCollectionTransfer());
    }

    /**
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getNodePath(int $idCategoryNode, LocaleTransfer $localeTransfer)
    {
        /** @var \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery $categoryPathQuery */
        $categoryPathQuery = $this->queryNodePath($idCategoryNode, $localeTransfer->getIdLocale())
            ->clearSelectColumns()
            ->addSelectColumn(static::COL_CATEGORY_NAME);

        /** @var string[] $pathTokens */
        $pathTokens = $categoryPathQuery->find();

        return implode('/', $pathTokens);
    }

    /**
     * @param int $idNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    protected function queryNodePath(
        $idNode,
        $idLocale
    ): SpyCategoryNodeQuery {
        $depth = 0;
        $nodeQuery = SpyCategoryNodeQuery::create();

        $nodeQuery
            ->useClosureTableQuery()
                ->orderByFkCategoryNodeDescendant(Criteria::DESC)
                ->orderByDepth(Criteria::DESC)
                ->filterByFkCategoryNodeDescendant($idNode)
                ->filterByDepth($depth, Criteria::NOT_EQUAL)
            ->endUse()
            ->useCategoryQuery()
                ->useAttributeQuery()
                    ->filterByFkLocale($idLocale)
                ->endUse()
            ->endUse();

        $nodeQuery->setFormatter(new PropelArraySetFormatter());

        return $nodeQuery;
    }

    /**
     * @param int[] $idsCategory
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function findCategoryTransferCollectionByCategoryIds(array $idsCategory, int $idLocale): CategoryCollectionTransfer
    {
        $spyCategoryCollection = $this->queryCategoryCollectionByCategoryIds($idsCategory, $idLocale)->find();

        return $this->getFactory()
            ->createCategoryMapper()
            ->mapCategoryCollection($spyCategoryCollection, new CategoryCollectionTransfer());
    }

    /**
     * @param int[] $idsCategory
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    protected function queryCategoryCollectionByCategoryIds(array $idsCategory, int $idLocale): SpyCategoryQuery
    {
        return $this->getFactory()->createCategoryQuery()
            ->joinAttribute()
            ->innerJoinNode()
            ->addAnd(
                SpyCategoryAttributeTableMap::COL_FK_LOCALE,
                $idLocale,
                Criteria::EQUAL
            )
            ->addAnd(
                SpyCategoryTableMap::COL_ID_CATEGORY,
                $idsCategory,
                Criteria::IN
            )
            ->withColumn(SpyCategoryTableMap::COL_ID_CATEGORY, 'id_category')
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name')
            ->withColumn(SpyCategoryTableMap::COL_CATEGORY_KEY, 'category_key')
            ->withColumn(SpyCategoryTableMap::COL_IS_ACTIVE, 'is_active')
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, 'id_category_node')
            ->addAscendingOrderByColumn(SpyCategoryAttributeTableMap::COL_NAME);
    }
}
