<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer;

/**
 * Implement this plugin interface to define a strategy to divide order items into groups to create/update/delete/skip.
 */
interface SalesOrderAmendmentItemCollectorStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if the strategy is applicable for the provided quote and order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer, OrderTransfer $orderTransfer): bool;

    /**
     * Specification:
     * - Returns `SalesOrderAmendmentItemCollectionTransfer` with items divided into groups to create/update/delete order items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer
     */
    public function collect(
        QuoteTransfer $quoteTransfer,
        OrderTransfer $orderTransfer
    ): SalesOrderAmendmentItemCollectionTransfer;
}
