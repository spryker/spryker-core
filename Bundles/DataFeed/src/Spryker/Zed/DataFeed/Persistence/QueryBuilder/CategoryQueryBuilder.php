<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Persistence\QueryBuilder;

use Generated\Shared\Transfer\CategoryFeedJoinTransfer;
use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Generated\Shared\Transfer\DataFeedDateFilterTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryQueryBuilder extends QueryBuilderAbstract implements QueryBuilderInterface
{

    /**
     * @param CategoryQueryContainerInterface $categoryQueryContainer
     */
    protected $categoryQueryContainer;

    /**
     * @param CategoryQueryContainerInterface $categoryQueryContainer
     */
    public function __construct(CategoryQueryContainerInterface $categoryQueryContainer)
    {
        $this->categoryQueryContainer = $categoryQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function getDataFeed(DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        $categoryFeedJoinTransfer = $dataFeedConditionTransfer->getCategoryFeedJoin();
        $localeTransfer = $dataFeedConditionTransfer->getLocale();
        $categoryQuery = $this->categoryQueryContainer
            ->queryCategory($localeTransfer->getIdLocale());

        $this->applyJoins($categoryQuery, $categoryFeedJoinTransfer, $localeTransfer);
        $this->applyPagination($categoryQuery, $dataFeedConditionTransfer->getPagination());
        $this->applyDateFilter($categoryQuery, $dataFeedConditionTransfer->getDateFilter());

        return $categoryQuery;
    }

    /**
     * @param SpyCategoryQuery $categoryQuery
     * @param CategoryFeedJoinTransfer $categoryFeedJoinTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function applyJoins(
        SpyCategoryQuery $categoryQuery,
        CategoryFeedJoinTransfer $categoryFeedJoinTransfer,
        LocaleTransfer $localeTransfer
    ) {
        //todo: implement
    }

    /**
     * @param SpyCategoryQuery $categoryQuery
     * @param DataFeedDateFilterTransfer $dataFeedDateFilterTransfer
     *
     * @return void
     */
    protected function applyDateFilter(
        SpyCategoryQuery $categoryQuery,
        DataFeedDateFilterTransfer $dataFeedDateFilterTransfer
    ) {
        //todo: implement
    }

}
