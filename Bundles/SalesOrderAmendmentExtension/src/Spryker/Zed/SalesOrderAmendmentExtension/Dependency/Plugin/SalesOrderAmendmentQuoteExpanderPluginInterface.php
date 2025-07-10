<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer;

/**
 * Implement this plugin interface to expand sales order amendment quotes.
 */
interface SalesOrderAmendmentQuoteExpanderPluginInterface
{
    /**
     * Specification:
     * - Extends sales order amendment quotes with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer $salesOrderAmendmentQuoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer
     */
    public function expand(
        SalesOrderAmendmentQuoteCollectionTransfer $salesOrderAmendmentQuoteCollectionTransfer
    ): SalesOrderAmendmentQuoteCollectionTransfer;
}
