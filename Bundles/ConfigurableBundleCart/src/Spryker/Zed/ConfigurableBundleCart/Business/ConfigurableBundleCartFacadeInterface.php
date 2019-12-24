<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
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

    /**
     * Specification:
     * - Applicable to items which have configured bundle properties.
     * - Updates configured bundle item quantity per slot.
     * - Returns modified CartChangeTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandConfiguredBundleItemsWithQuantityPerSlot(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

    /**
     * Specification:
     * - Applicable to items which have configured bundle properties.
     * - Sets group key for each item with ItemTransfer::getConfiguredBundle property filled.
     * - Returns modified CartChangeTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandConfiguredBundleItemsWithGroupKey(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

    /**
     * Specification:
     * - Applicable to items which have configured bundle properties.
     * - Checks configurable bundle template slot combinations.
     * - Sets error message in case wrong combination of slots.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkConfiguredBundleTemplateSlotCombination(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;
}
