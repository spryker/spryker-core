<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AbstractProductDataFeed\Persistence;

use Generated\Shared\Transfer\AbstractProductDataFeedTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

/**
 * @method \Spryker\Zed\AbstractProductDataFeed\Persistence\AbstractProductDataFeedPersistenceFactory getFactory()
 */
class AbstractProductDataFeedQueryContainer extends AbstractQueryContainer implements ProductDataFeedQueryContainerInterface
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
     * @param AbstractProductDataFeedTransfer $productDataFeedTransfer
     *
     * @return SpyProductAbstractQuery
     */
    public function queryAbstractProductDataFeed(AbstractProductDataFeedTransfer $productDataFeedTransfer)
    {
        $abstractProductQuery = $this->productQueryContainer
            ->queryProductAbstract();

        $abstractProductQuery = $this->getFactory()
            ->getAbstractProductJoinQuery()
            ->applyJoins($abstractProductQuery, $productDataFeedTransfer);

        $abstractProductQuery = $this->applyGroupings($abstractProductQuery);
        $abstractProductQuery = $this->filterByUpdatedAt($abstractProductQuery, $productDataFeedTransfer);


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
     * @param SpyProductAbstractQuery $abstractProductQuery
     * @param AbstractProductDataFeedTransfer $abstractProductDataFeedTransfer
     *
     * @return SpyProductAbstractQuery
     */
    protected function filterByUpdatedAt(
        SpyProductAbstractQuery $abstractProductQuery,
        AbstractProductDataFeedTransfer $abstractProductDataFeedTransfer
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
