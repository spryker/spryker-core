<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Business;

use Generated\Shared\Transfer\QuoteTransfer;

interface ConfigurableBundleCartFacadeInterface
{
    /**
     * Specification:
     * - Applies to items that have configurable properties.
     * - Requires quantityPerSlot inside ConfigurableBundleItemTransfer.
     * - Updates configured bundle quantity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function updateConfiguredBundleQuantityForQuote(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Applies to items that have configurable properties.
     * - Requires quantity inside ConfigurableBundleTransfer.
     * - Updates configured bundle quantity per slot.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function updateConfiguredBundleQuantityPerSlotForQuote(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Maps configured bundles from quote.
     * - Requires quantityPerSlot inside ConfigurableBundleItemTransfer.
     * - Requires quantity inside ConfigurableBundleTransfer.
     * - Checks configured bundle quantity correctness to item quantity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkConfiguredBundleQuantityInQuote(QuoteTransfer $quoteTransfer): bool;
}
