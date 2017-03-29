<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceDataFeed\Persistence;

use Generated\Shared\Transfer\PriceDataFeedTransfer;
use Orm\Zed\Price\Persistence\Map\SpyPriceProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Price\Persistence\SpyPriceProductQuery;
use Spryker\Zed\Price\Persistence\PriceQueryContainerInterface;

/**
 * @method \Spryker\Zed\PriceDataFeed\Persistence\PriceDataFeedPersistenceFactory getFactory()
 */
class PriceDataFeedQueryContainer extends AbstractQueryContainer implements PriceDataFeedQueryContainerInterface
{

    const PRICE_QUERY_SELECT_COLUMNS = 'PRICE_QUERY_SELECT_COLUMNS';
    const PRICE_TYPE_COLUMNS = 'PRICE_TYPE_COLUMNS';

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
     * @api
     *
     * @param PriceDataFeedTransfer $priceDataFeedTransfer
     *
     * @return SpyPriceProductQuery
     *
     */
    public function getPriceDataFeedQuery(PriceDataFeedTransfer $priceDataFeedTransfer)
    {
        $productPriceQuery = $this->priceQueryContainer
            ->queryPriceProduct();

        $productPriceQuery = $this->applyJoins($productPriceQuery, $priceDataFeedTransfer);
        $productPriceQuery = $this->applyGroupings($productPriceQuery);

        return $productPriceQuery;
    }

    /**
     * @param \Orm\Zed\Price\Persistence\SpyPriceProductQuery $productPriceQuery
     * @param PriceDataFeedTransfer $priceDataFeedTransfer
     *
     * @return SpyPriceProductQuery
     *
     */
    protected function applyJoins(
        SpyPriceProductQuery $productPriceQuery,
        PriceDataFeedTransfer $priceDataFeedTransfer = null
    )
    {
        if ($priceDataFeedTransfer !== null) {
            $productPriceQuery = $this->joinPriceTypes($productPriceQuery, $priceDataFeedTransfer);
        }

        return $productPriceQuery;
    }

    /**
     * @param \Orm\Zed\Price\Persistence\SpyPriceProductQuery $productPriceQuery
     * @param PriceDataFeedTransfer $priceDataFeedTransfer
     *
     * @return SpyPriceProductQuery
     *
     */
    protected function joinPriceTypes(
        SpyPriceProductQuery $productPriceQuery,
        PriceDataFeedTransfer $priceDataFeedTransfer
    )
    {
        if ($priceDataFeedTransfer->getIsJoinType()) {
            $productPriceQuery->joinPriceType();
        }

        return $productPriceQuery;
    }

    /**
     * @param SpyPriceProductQuery $priceProductQuery
     *
     * @return SpyPriceProductQuery
     */
    protected function applyGroupings(SpyPriceProductQuery $priceProductQuery)
    {
        $priceProductQuery->groupBy(SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT);

        return $priceProductQuery;
    }

}
