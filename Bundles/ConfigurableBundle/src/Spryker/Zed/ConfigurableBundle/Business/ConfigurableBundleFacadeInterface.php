<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business;

use Generated\Shared\Transfer\ConfiguredBundleFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleCollectionTransfer;

interface ConfigurableBundleFacadeInterface
{
    /**
     * Specification:
     * - Persists configured bundles from ItemTransfer in Quote to sales_order configured bundle tables.
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
     * - Retrieves sales order configured bundles entities.
     * - Filters by template uuid when provided.
     * - Filters by slot uuid when provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfiguredBundleFilterTransfer $configuredBundleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleCollectionTransfer
     */
    public function getSalesOrderConfiguredBundleCollectionByFilter(
        ConfiguredBundleFilterTransfer $configuredBundleFilterTransfer
    ): SalesOrderConfiguredBundleCollectionTransfer;
}
