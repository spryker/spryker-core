<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDataFeed\Persistence;

use Generated\Shared\Transfer\CategoryDataFeedTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\CategoryDataFeed\Persistence\CategoryDataFeedPersistenceFactory getFactory()
 */
class CategoryDataFeedQueryContainer extends AbstractQueryContainer implements CategoryDataFeedQueryContainerInterface
{
    public const UPDATED_FROM_CONDITION = 'UPDATED_FROM_CONDITION';
    public const UPDATED_TO_CONDITION = 'UPDATED_TO_CONDITION';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryDataFeedTransfer $categoryDataFeedTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategoryDataFeed(CategoryDataFeedTransfer $categoryDataFeedTransfer)
    {
        $categoryQuery = $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryCategory($categoryDataFeedTransfer->getIdLocale());

        $categoryQuery = $this->applyJoins($categoryQuery, $categoryDataFeedTransfer);
        $categoryQuery = $this->applyDateFilters($categoryQuery, $categoryDataFeedTransfer);
        $categoryQuery = $this->applyGroupings($categoryQuery);

        return $categoryQuery;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryQuery $categoryQuery
     * @param \Generated\Shared\Transfer\CategoryDataFeedTransfer $categoryDataFeedTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    protected function applyJoins(
        SpyCategoryQuery $categoryQuery,
        CategoryDataFeedTransfer $categoryDataFeedTransfer
    ) {
        $categoryQuery->innerJoinNode();

        $categoryQuery = $this->joinProducts(
            $categoryQuery,
            $categoryDataFeedTransfer
        );

        return $categoryQuery;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryQuery $categoryQuery
     * @param \Generated\Shared\Transfer\CategoryDataFeedTransfer $categoryDataFeedTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    protected function joinProducts(
        SpyCategoryQuery $categoryQuery,
        CategoryDataFeedTransfer $categoryDataFeedTransfer
    ) {
        if (!$categoryDataFeedTransfer->getJoinAbstractProduct()) {
            return $categoryQuery;
        }

        $categoryQuery
            ->useSpyProductCategoryQuery(null, Criteria::LEFT_JOIN)
                ->useSpyProductAbstractQuery(null, Criteria::LEFT_JOIN)
                    ->useSpyProductAbstractLocalizedAttributesQuery()
                    ->endUse()
                ->endUse()
            ->endUse();

        return $categoryQuery;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryQuery $categoryQuery
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    protected function applyGroupings(SpyCategoryQuery $categoryQuery)
    {
        $categoryQuery->groupBy(SpyCategoryTableMap::COL_ID_CATEGORY);

        return $categoryQuery;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryQuery $categoryQuery
     * @param \Generated\Shared\Transfer\CategoryDataFeedTransfer $categoryDataFeedTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    protected function applyDateFilters(
        SpyCategoryQuery $categoryQuery,
        CategoryDataFeedTransfer $categoryDataFeedTransfer
    ) {
        if ($categoryDataFeedTransfer->getUpdatedFrom()) {
            $categoryQuery->condition(
                self::UPDATED_FROM_CONDITION,
                SpyCategoryAttributeTableMap::COL_UPDATED_AT . ' >= ?',
                $categoryDataFeedTransfer->getUpdatedFrom()
            )->where([self::UPDATED_FROM_CONDITION]);
        }

        if ($categoryDataFeedTransfer->getUpdatedTo()) {
            $categoryQuery->condition(
                self::UPDATED_TO_CONDITION,
                SpyCategoryAttributeTableMap::COL_UPDATED_AT . ' <= ?',
                $categoryDataFeedTransfer->getUpdatedTo()
            )->where([self::UPDATED_TO_CONDITION]);
        }

        return $categoryQuery;
    }
}
