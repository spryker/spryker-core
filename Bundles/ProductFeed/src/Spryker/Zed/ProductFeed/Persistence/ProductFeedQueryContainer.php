<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductFeed\Persistence;

use Generated\Shared\Transfer\ProductFeedConditionTransfer;
use Generated\Shared\Transfer\ProductFeedPaginationTransfer;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductFeed\Persistence\ProductFeedPersistenceFactory getFactory()
 */
class ProductFeedQueryContainer extends AbstractQueryContainer implements ProductFeedQueryContainerInterface
{

    /**
     * @api
     *
     * @param ProductFeedConditionTransfer $productFeedConditionTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductFeedCollection(ProductFeedConditionTransfer $productFeedConditionTransfer)
    {
        $productQuery = $this->getFactory()
            ->getProductQueryContainer()
            ->queryProduct();

        $this->applyJoins($productQuery, $productFeedConditionTransfer);
        $this->applyPagination($productQuery, $productFeedConditionTransfer->getPagination());

        return $productQuery;
    }

    /**
     * @param SpyProductQuery $productQuery
     * @param ProductFeedConditionTransfer $productFeedConditionTransfer
     *
     * @return void
     */
    protected function applyJoins(
        SpyProductQuery $productQuery,
        ProductFeedConditionTransfer $productFeedConditionTransfer
    ) {
        //todo: implement joins
    }

    /**
     * @param SpyProductQuery $productQuery
     * @param ProductFeedPaginationTransfer $paginationTransfer
     *
     * @return void
     */
    protected function applyPagination(
        SpyProductQuery $productQuery,
        ProductFeedPaginationTransfer $paginationTransfer
    ) {
        $this->setQueryLimit($productQuery, $paginationTransfer);
        $this->setQueryOffset($productQuery, $paginationTransfer);
    }

    /**
     * @param SpyProductQuery $productQuery
     * @param ProductFeedPaginationTransfer $paginationTransfer
     *
     * @return void
     */
    protected function setQueryLimit(SpyProductQuery $productQuery, ProductFeedPaginationTransfer $paginationTransfer)
    {
        if ($paginationTransfer->getLimit()) {
            $productQuery
                ->setLimit($paginationTransfer->getLimit());
        }
    }

    /**
     * @param SpyProductQuery $productQuery
     * @param ProductFeedPaginationTransfer $paginationTransfer
     *
     * @return void
     */
    protected function setQueryOffset(SpyProductQuery $productQuery, ProductFeedPaginationTransfer $paginationTransfer)
    {
        if ($paginationTransfer->getOffset()) {
            $productQuery
                ->setOffset($paginationTransfer->getOffset());
        }
    }

}
