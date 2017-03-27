<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Persistence\QueryBuilder;

use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Generated\Shared\Transfer\PriceFeedJoinTransfer;
use Orm\Zed\Price\Persistence\SpyPriceProductQuery;
use Spryker\Zed\Price\Persistence\PriceQueryContainerInterface;

class PriceQueryBuilder extends QueryBuilderAbstract implements QueryBuilderInterface
{

    /**
     * @param \Spryker\Zed\Price\Persistence\PriceQueryContainerInterface $priceQueryContainer
     */
    protected $priceQueryContainer;

    /**
     * @param \Spryker\Zed\Price\Persistence\PriceQueryContainerInterface $priceQueryContainer
     */
    public function __construct(PriceQueryContainerInterface $priceQueryContainer)
    {
        $this->priceQueryContainer = $priceQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function getDataFeed(DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        $priceFeedJoinTransfer = $dataFeedConditionTransfer->getPriceFeedJoin();
        $productPriceQuery = $this->priceQueryContainer
            ->queryPriceProduct();

        $productPriceQuery = $this->applyJoins($productPriceQuery, $priceFeedJoinTransfer);

        return $productPriceQuery;
    }

    /**
     * @param \Orm\Zed\Price\Persistence\SpyPriceProductQuery $productPriceQuery
     * @param \Generated\Shared\Transfer\PriceFeedJoinTransfer $priceFeedJoinTransfer
     *
     * @return SpyPriceProductQuery
     */
    protected function applyJoins(
        SpyPriceProductQuery $productPriceQuery,
        PriceFeedJoinTransfer $priceFeedJoinTransfer = null
    ) {
        if ($priceFeedJoinTransfer !== null) {
            $productPriceQuery = $this->joinPriceTypes($productPriceQuery, $priceFeedJoinTransfer);
        }

        return $productPriceQuery;
    }

    /**
     * @param \Orm\Zed\Price\Persistence\SpyPriceProductQuery $productPriceQuery
     * @param \Generated\Shared\Transfer\DataFeedDateFilterTransfer $dataFeedDateFilterTransfer
     *
     * @return void
     */
//    protected function applyDateFilter(
//        SpyPriceProductQuery $productPriceQuery,
//        DataFeedDateFilterTransfer $dataFeedDateFilterTransfer
//    ) {
//        //todo: implement
//    }

    /**
     * @param \Orm\Zed\Price\Persistence\SpyPriceProductQuery $productPriceQuery
     * @param \Generated\Shared\Transfer\PriceFeedJoinTransfer $priceFeedJoinTransfer
     *
     * @return SpyPriceProductQuery
     */
    protected function joinPriceTypes($productPriceQuery, $priceFeedJoinTransfer)
    {
        if ($priceFeedJoinTransfer->getIsJoinType()) {
            $productPriceQuery->joinWithPriceType();
        }

        return $productPriceQuery;
    }

}
