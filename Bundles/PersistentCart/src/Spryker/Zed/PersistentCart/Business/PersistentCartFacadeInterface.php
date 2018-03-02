<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business;

use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method \Spryker\Zed\PersistentCart\Business\PersistentCartBusinessFactory getFactory()
 */
interface PersistentCartFacadeInterface
{
    /**
     *  Adds item(s) to the quote. Each item gets additional information (e.g. price).
     *
     * Specification:
     *  - Run cart pre check plugins
     *  - For each new item run the item expander plugins (requires a SKU for each new item)
     *  - Add new item(s) to quote (Requires a quantity > 0 for each new item)
     *  - Group items in quote (-> ItemGrouper)
     *  - Recalculate quote (-> Calculation)
     *  - Add success message to messenger (-> Messenger)
     *  - Return updated quote
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function add(PersistentCartChangeTransfer $persistentCartChangeTransfer);

    /**
     * Specification:
     *  - For each new item run the item expander plugins (requires a SKU for each new item)
     *  - Decreases the given quantity for the given item(s) from the quote
     *  - Recalculate quote (-> Calculation)
     *  - Add success message to messenger (-> Messenger)
     *  - Return updated quote
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function remove(PersistentCartChangeTransfer $persistentCartChangeTransfer);

    /**
     * Specification:
     *  - Reloads all items in cart anew, it recreates all items transfer, reads new prices, options, bundles.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function reloadItems(QuoteTransfer $quoteTransfer);
}
