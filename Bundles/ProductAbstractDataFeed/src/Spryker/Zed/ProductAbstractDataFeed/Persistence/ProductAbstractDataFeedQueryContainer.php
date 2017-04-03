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
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

/**
 * @method \Spryker\Zed\ProductAbstractDataFeed\Persistence\ProductAbstractDataFeedPersistenceFactory getFactory()
 */
class ProductAbstractDataFeedQueryContainer extends AbstractQueryContainer implements ProductAbstractDataFeedQueryContainerInterface
{

    const UPDATED_FROM_CONDITION = 'UPDATED_FROM_CONDITION';
    const UPDATED_TO_CONDITION = 'UPDATED_TO_CONDITION';

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    protected $productQueryContainer;

    /**
     * @api
     *
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer)
    {
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractDataFeedTransfer|null $productDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery
     */
    public function queryAbstractProductDataFeed(ProductAbstractDataFeedTransfer $productDataFeedTransfer = null)
    {
        $abstractProductQuery = $this->productQueryContainer
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
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     *
     * @return \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery
     */
    protected function applyGroupings(SpyProductAbstractQuery $abstractProductQuery)
    {
        $abstractProductQuery->groupBy(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery
     */
    protected function filterByUpdatedAt(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
    ) {
        if ($abstractProductDataFeedTransfer->getUpdatedFrom()) {
            $abstractProductQuery->condition(
                self::UPDATED_FROM_CONDITION,
                SpyProductAbstractLocalizedAttributesTableMap::COL_UPDATED_AT . ' > ?',
                $abstractProductDataFeedTransfer->getUpdatedFrom()
            )->where([self::UPDATED_FROM_CONDITION]);
        }

        if ($abstractProductDataFeedTransfer->getUpdatedTo()) {
            $abstractProductQuery->condition(
                self::UPDATED_TO_CONDITION,
                SpyProductAbstractLocalizedAttributesTableMap::COL_UPDATED_AT . ' < ?',
                $abstractProductDataFeedTransfer->getUpdatedTo()
            )->where([self::UPDATED_TO_CONDITION]);
        }

        return $abstractProductQuery;
    }

}
