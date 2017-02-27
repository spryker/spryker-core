<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Persistence\QueryBuilder;

use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Generated\Shared\Transfer\DataFeedDateFilterTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StockFeedJoinTransfer;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;

class StockQueryBuilder extends QueryBuilderAbstract implements QueryBuilderInterface
{

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface $stockQueryContainer
     */
    protected $stockQueryContainer;

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface $stockQueryContainer
     */
    public function __construct(StockQueryContainerInterface $stockQueryContainer)
    {
        $this->stockQueryContainer = $stockQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
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
     * @param \Orm\Zed\Stock\Persistence\SpyStockProductQuery $stockProductQuery
     * @param \Generated\Shared\Transfer\StockFeedJoinTransfer $stockFeedJoinTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
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
     * @param \Orm\Zed\Stock\Persistence\SpyStockProductQuery $stockProductQuery
     * @param \Generated\Shared\Transfer\DataFeedDateFilterTransfer $dataFeedDateFilterTransfer
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
