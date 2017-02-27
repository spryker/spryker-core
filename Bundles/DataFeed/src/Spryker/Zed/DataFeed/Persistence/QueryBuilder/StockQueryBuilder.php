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
use Generated\Shared\Transfer\PriceFeedJoinTransfer;
use Generated\Shared\Transfer\StockFeedJoinTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Orm\Zed\Price\Persistence\SpyPriceProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\Price\Persistence\PriceQueryContainerInterface;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;

class StockQueryBuilder extends QueryBuilderAbstract implements QueryBuilderInterface
{

    /**
     * @param StockQueryContainerInterface $stockQueryContainer
     */
    protected $stockQueryContainer;

    /**
     * @param StockQueryContainerInterface $stockQueryContainer
     */
    public function __construct(StockQueryContainerInterface $stockQueryContainer)
    {
        $this->stockQueryContainer = $stockQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return SpyStockProductQuery
     */
    public function getDataFeed(DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        $stockFeedJoinTransfer = $dataFeedConditionTransfer->getStockFeedJoin();
        $localeTransfer = $dataFeedConditionTransfer->getLocale();
        $stockProductQuery = $this->stockQueryContainer
            ->queryAllStockProducts();

        $this->applyJoins($stockProductQuery, $stockFeedJoinTransfer, $localeTransfer);
        $this->applyPagination($stockProductQuery, $dataFeedConditionTransfer->getPagination());
        $this->applyDateFilter($stockProductQuery, $dataFeedConditionTransfer->getDateFilter());

        return $stockProductQuery;
    }

    /**
     * @param SpyStockProductQuery $stockProductQuery
     * @param StockFeedJoinTransfer $stockFeedJoinTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function applyJoins(
        SpyStockProductQuery $stockProductQuery,
        StockFeedJoinTransfer $stockFeedJoinTransfer,
        LocaleTransfer $localeTransfer
    ) {
        //todo: implement
    }

    /**
     * @param SpyStockProductQuery $stockProductQuery
     * @param DataFeedDateFilterTransfer $dataFeedDateFilterTransfer
     *
     * @return void
     */
    protected function applyDateFilter(
        SpyStockProductQuery $stockProductQuery,
        DataFeedDateFilterTransfer $dataFeedDateFilterTransfer
    ) {
        //todo: implement
    }

}
