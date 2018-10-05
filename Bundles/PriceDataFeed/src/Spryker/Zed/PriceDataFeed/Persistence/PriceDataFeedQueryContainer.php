<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceDataFeed\Persistence;

use Generated\Shared\Transfer\PriceDataFeedTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\PriceDataFeed\Persistence\PriceDataFeedPersistenceFactory getFactory()
 */
class PriceDataFeedQueryContainer extends AbstractQueryContainer implements PriceDataFeedQueryContainerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceDataFeedTransfer|null $priceDataFeedTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceDataFeed(?PriceDataFeedTransfer $priceDataFeedTransfer = null)
    {
        $productPriceQuery = $this->getFactory()
            ->getPriceProductQueryContainer()
            ->queryPriceProduct();

        $productPriceQuery = $this->applyJoins($productPriceQuery, $priceDataFeedTransfer);

        return $productPriceQuery;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery $productPriceQuery
     * @param \Generated\Shared\Transfer\PriceDataFeedTransfer|null $priceDataFeedTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    protected function applyJoins(
        SpyPriceProductQuery $productPriceQuery,
        ?PriceDataFeedTransfer $priceDataFeedTransfer = null
    ) {

        if ($priceDataFeedTransfer !== null) {
            $productPriceQuery = $this->joinPriceTypes($productPriceQuery, $priceDataFeedTransfer);
        }

        return $productPriceQuery;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery $productPriceQuery
     * @param \Generated\Shared\Transfer\PriceDataFeedTransfer $priceDataFeedTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    protected function joinPriceTypes(
        SpyPriceProductQuery $productPriceQuery,
        PriceDataFeedTransfer $priceDataFeedTransfer
    ) {

        if ($priceDataFeedTransfer->getJoinPriceType()) {
            $productPriceQuery->joinPriceType();
        }

        return $productPriceQuery;
    }
}
