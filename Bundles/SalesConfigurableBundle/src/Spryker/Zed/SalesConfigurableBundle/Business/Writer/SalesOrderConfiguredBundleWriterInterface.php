<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business\Writer;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;

interface SalesOrderConfiguredBundleWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSalesOrderConfiguredBundlesFromQuote(QuoteTransfer $quoteTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function updateSalesOrderConfiguredBundles(
        SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
    ): SalesOrderItemCollectionResponseTransfer;
}
