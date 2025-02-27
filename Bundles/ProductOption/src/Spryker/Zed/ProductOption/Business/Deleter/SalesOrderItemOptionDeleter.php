<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\Deleter;

use Generated\Shared\Transfer\SalesOrderItemOptionCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemOptionCollectionResponseTransfer;
use Spryker\Zed\ProductOption\Persistence\ProductOptionEntityManagerInterface;

class SalesOrderItemOptionDeleter implements SalesOrderItemOptionDeleterInterface
{
    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionEntityManagerInterface $productOptionEntityManager
     */
    public function __construct(protected ProductOptionEntityManagerInterface $productOptionEntityManager)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemOptionCollectionDeleteCriteriaTransfer $salesOrderItemOptionCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemOptionCollectionResponseTransfer
     */
    public function deleteSalesOrderItemOptionCollection(
        SalesOrderItemOptionCollectionDeleteCriteriaTransfer $salesOrderItemOptionCollectionDeleteCriteriaTransfer
    ): SalesOrderItemOptionCollectionResponseTransfer {
        if ($salesOrderItemOptionCollectionDeleteCriteriaTransfer->getSalesOrderItemIds()) {
            $this->productOptionEntityManager->deleteSalesOrderItemProductOptionsBySalesOrderItemIds(
                $salesOrderItemOptionCollectionDeleteCriteriaTransfer->getSalesOrderItemIds(),
            );
        }

        return new SalesOrderItemOptionCollectionResponseTransfer();
    }
}
