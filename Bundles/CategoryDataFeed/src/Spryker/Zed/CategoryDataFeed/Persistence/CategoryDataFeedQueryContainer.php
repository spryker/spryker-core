<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDataFeed\Persistence;

use Generated\Shared\Transfer\CategoryDataFeedTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\CategoryDataFeed\Persistence\CategoryDataFeedPersistenceFactory getFactory()
 */
class CategoryDataFeedQueryContainer extends AbstractQueryContainer implements CategoryDataFeedQueryContainerInterface
{

    const CATEGORY_QUERY_SELECT_COLUMNS = 'CATEGORY_QUERY_SELECT_COLUMNS';
    const PRODUCTS_COLUMNS = 'PRODUCTS_COLUMNS';
    const LOCALE_FILTER_VALUE = 'LOCALE_FILTER_VALUE';
    const LOCALE_FILTER_CRITERIA = 'LOCALE_FILTER_CRITERIA';

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $categoryQueryContainer
     */
    protected $categoryQueryContainer;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $categoryQueryContainer
     */
    public function __construct(CategoryQueryContainerInterface $categoryQueryContainer)
    {
        $this->categoryQueryContainer = $categoryQueryContainer;
    }

    /**
     * @api
     *
     * @param CategoryDataFeedTransfer $categoryDataFeedTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function getCategoryDataFeedQuery(CategoryDataFeedTransfer $categoryDataFeedTransfer)
    {
        $categoryQuery = $this->categoryQueryContainer
            ->queryCategory($categoryDataFeedTransfer->getLocaleId());

        $categoryQuery = $this->applyJoins($categoryQuery, $categoryDataFeedTransfer);

        return $categoryQuery;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryQuery $categoryQuery
     * @param CategoryDataFeedTransfer $categoryDataFeedTransfer
     *
     * @return SpyCategoryQuery
     */
    protected function applyJoins(
        SpyCategoryQuery $categoryQuery,
        CategoryDataFeedTransfer $categoryDataFeedTransfer
    ) {
        $categoryQuery = $this->joinProducts(
            $categoryQuery,
            $categoryDataFeedTransfer
        );

        return $categoryQuery;
    }

    /**
     * @param SpyCategoryQuery $categoryQuery
     * @param CategoryDataFeedTransfer $categoryDataFeedTransfer
     *
     * @return SpyCategoryQuery
     */
    protected function joinProducts(
        SpyCategoryQuery $categoryQuery,
        CategoryDataFeedTransfer $categoryDataFeedTransfer
    ) {
        if ($categoryDataFeedTransfer->getIsJoinProduct()) {
            $localeTransferConditions = $this->getIdLocaleFilterConditions($categoryDataFeedTransfer->getLocaleId());

            $categoryQuery
                ->useSpyProductCategoryQuery(null, Criteria::LEFT_JOIN)
                    ->useSpyProductAbstractQuery(null, Criteria::LEFT_JOIN)
                        ->useSpyProductAbstractLocalizedAttributesQuery()
                            ->filterByFkLocale(
                                $localeTransferConditions[self::LOCALE_FILTER_VALUE],
                                $localeTransferConditions[self::LOCALE_FILTER_CRITERIA]
                            )
                        ->endUse()
                    ->endUse()
                ->endUse();
        }

        return $categoryQuery;
    }

    /**
     * @param integer $localeId
     *
     * @return array
     */
    protected function getIdLocaleFilterConditions($localeId = null)
    {
        if ($localeId !== null) {
            $filterCriteria = Criteria::EQUAL;
            $filterValue = $localeId;
        } else {
            $filterCriteria = Criteria::NOT_EQUAL;
            $filterValue = null;
        }

        return [
            self::LOCALE_FILTER_VALUE => $filterValue,
            self::LOCALE_FILTER_CRITERIA => $filterCriteria,
        ];
    }

}
