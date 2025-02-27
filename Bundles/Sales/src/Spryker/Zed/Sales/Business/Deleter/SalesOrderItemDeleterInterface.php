<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Deleter;

use Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;

interface SalesOrderItemDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer $salesOrderItemCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function deleteSalesOrderItemCollection(
        SalesOrderItemCollectionDeleteCriteriaTransfer $salesOrderItemCollectionDeleteCriteriaTransfer
    ): SalesOrderItemCollectionResponseTransfer;
}
