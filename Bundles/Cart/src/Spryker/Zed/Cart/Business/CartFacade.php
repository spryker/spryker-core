<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Cart\Business\CartBusinessFactory getFactory()
 */
class CartFacade extends AbstractFacade implements CartFacadeInterface
{

    /**
     * Adds item(s) to the quote. Each item gets additonal informations (e.g. price).
     *
     * Specification:
     * - For each new item run the item expander plugins (requires a SKU for each new item)
     * - Add new item(s) to quote (Requires a quantity > 0 for each new item)
     * - Group items in quote (-> ItemGrouper)
     * - Recalculate quote (-> Calculation)
     * - Add success message to messenger (-> Messenger)
     * - Return updated quote
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function add(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()->createCartOperation()->add($cartChangeTransfer);
    }

    /**
     * Remove item(s) from the quote.
     *
     * Specification:
     * - For each new item run the item expander plugins (requires a SKU for each new item)
     * - Decreases the given quantity for the given item(s) from the quote
     * - Recalculate quote (-> Calculation)
     * - Add success message to messenger (-> Messenger)
     * - Return updated quote
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function remove(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()->createCartOperation()->remove($cartChangeTransfer);
    }

}
