<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Business\Deleter;

use Generated\Shared\Transfer\SalesOrderItemServicePointCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointCollectionResponseTransfer;
use Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointEntityManagerInterface;

class SalesOrderItemServicePointDeleter implements SalesOrderItemServicePointDeleterInterface
{
    /**
     * @param \Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointEntityManagerInterface $salesServicePointEntityManager
     */
    public function __construct(protected SalesServicePointEntityManagerInterface $salesServicePointEntityManager)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemServicePointCollectionDeleteCriteriaTransfer $salesOrderItemServicePointCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemServicePointCollectionResponseTransfer
     */
    public function deleteSalesOrderItemServicePointCollection(
        SalesOrderItemServicePointCollectionDeleteCriteriaTransfer $salesOrderItemServicePointCollectionDeleteCriteriaTransfer
    ): SalesOrderItemServicePointCollectionResponseTransfer {
        if ($salesOrderItemServicePointCollectionDeleteCriteriaTransfer->getSalesOrderItemIds()) {
            $this->salesServicePointEntityManager->deleteSalesOrderItemServicePointsBySalesOrderItemIds(
                $salesOrderItemServicePointCollectionDeleteCriteriaTransfer->getSalesOrderItemIds(),
            );
        }

        return new SalesOrderItemServicePointCollectionResponseTransfer();
    }
}
