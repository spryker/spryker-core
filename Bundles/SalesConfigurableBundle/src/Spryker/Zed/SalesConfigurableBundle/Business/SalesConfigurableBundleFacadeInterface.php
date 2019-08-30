<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleFilterTransfer;

interface SalesConfigurableBundleFacadeInterface
{
    /**
     * Specification:
     * - Retrieves sales order configured bundles entities.
     * - Filters by template uuid when provided.
     * - Filters by slot uuid when provided.
     * - Filters by itemIds when provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleFilterTransfer $salesOrderConfiguredBundleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleCollectionTransfer
     */
    public function getSalesOrderConfiguredBundleCollectionByFilter(
        SalesOrderConfiguredBundleFilterTransfer $salesOrderConfiguredBundleFilterTransfer
    ): SalesOrderConfiguredBundleCollectionTransfer;

    /**
     * Specification:
     * - Persists configured bundles from ItemTransfer in Quote to sales_order configured bundle tables.
     * - Expects the configured bundle groupKey to be provided.
     * - Expects the configured bundle quantity to be provided.
     * - Expects the configured bundle template to be provided.
     * - Expects the configured bundle slot to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSalesOrderConfiguredBundlesFromQuote(QuoteTransfer $quoteTransfer): void;

    /**
     * Specification:
     * - Expands sales order by configured bundles.
     * - Expands ItemTransfer by configured bundle item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithConfiguredBundles(OrderTransfer $orderTransfer): OrderTransfer;
}
