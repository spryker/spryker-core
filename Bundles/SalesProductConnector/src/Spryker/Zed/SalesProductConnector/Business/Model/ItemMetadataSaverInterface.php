<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;

interface ItemMetadataSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveItemsMetadata(QuoteTransfer $quoteTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function updateOrderItemMetadata(
        SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
    ): SalesOrderItemCollectionResponseTransfer;
}
