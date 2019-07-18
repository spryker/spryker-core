<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAbstractDataFeed\Persistence;

use Generated\Shared\Transfer\ProductAbstractDataFeedTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductAbstractDataFeed\Persistence\ProductAbstractDataFeedPersistenceFactory getFactory()
 */
class ProductAbstractDataFeedQueryContainer extends AbstractQueryContainer implements ProductAbstractDataFeedQueryContainerInterface
{
    public const UPDATED_FROM_CONDITION = 'UPDATED_FROM_CONDITION';
    public const UPDATED_TO_CONDITION = 'UPDATED_TO_CONDITION';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractDataFeedTransfer|null $productDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAbstractProductDataFeed(?ProductAbstractDataFeedTransfer $productDataFeedTransfer = null)
    {
        $abstractProductQuery = $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract();

        if ($productDataFeedTransfer !== null) {
            $abstractProductQuery = $this->getFactory()
                ->getAbstractProductJoinQuery()
                ->applyJoins($abstractProductQuery, $productDataFeedTransfer);
            $abstractProductQuery = $this->filterByUpdatedAt($abstractProductQuery, $productDataFeedTransfer);
        }

        $abstractProductQuery = $this->applyGroupings($abstractProductQuery);

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $abstractProductQuery
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function applyGroupings(SpyProductAbstractQuery $abstractProductQuery)
    {
        $abstractProductQuery->groupBy(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function filterByUpdatedAt(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
    ) {
        if ($abstractProductDataFeedTransfer->getUpdatedFrom()) {
            $abstractProductQuery->condition(
                self::UPDATED_FROM_CONDITION,
                SpyProductAbstractLocalizedAttributesTableMap::COL_UPDATED_AT . ' >= ?',
                $abstractProductDataFeedTransfer->getUpdatedFrom()
            )->where([self::UPDATED_FROM_CONDITION]);
        }

        if ($abstractProductDataFeedTransfer->getUpdatedTo()) {
            $abstractProductQuery->condition(
                self::UPDATED_TO_CONDITION,
                SpyProductAbstractLocalizedAttributesTableMap::COL_UPDATED_AT . ' <= ?',
                $abstractProductDataFeedTransfer->getUpdatedTo()
            )->where([self::UPDATED_TO_CONDITION]);
        }

        return $abstractProductQuery;
    }
}
