<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Creator;

use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;

interface SalesOrderItemCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function createSalesOrderItemCollectionByQuote(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer;
}
