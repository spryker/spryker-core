<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addToCart(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()->createCartOperation()->add($cartChangeTransfer);
    }

    /**
     *
     * TODO FW Is this a duplicate of addToCart() ? If so please remove
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseQuantity(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()->createCartOperation()->increase($cartChangeTransfer);
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
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeFromCart(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()->createCartOperation()->remove($cartChangeTransfer);
    }

    /**
     * TODO FW Is this a duplicate of removeFromCart() ? If so please remove
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseQuantity(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()->createCartOperation()->decrease($cartChangeTransfer);
    }

}
