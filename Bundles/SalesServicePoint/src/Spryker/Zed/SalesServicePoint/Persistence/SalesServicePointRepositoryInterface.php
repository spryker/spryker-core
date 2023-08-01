<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Persistence;

use Generated\Shared\Transfer\SalesOrderItemServicePointCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointCriteriaTransfer;

interface SalesServicePointRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemServicePointCriteriaTransfer $salesOrderItemServicePointCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemServicePointCollectionTransfer
     */
    public function getSalesOrderItemServicePointCollection(
        SalesOrderItemServicePointCriteriaTransfer $salesOrderItemServicePointCriteriaTransfer
    ): SalesOrderItemServicePointCollectionTransfer;
}
