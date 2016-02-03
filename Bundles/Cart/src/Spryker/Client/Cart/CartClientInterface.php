<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Cart;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ItemTransfer;

interface CartClientInterface
{

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote();

    /**
     * @return void
     */
    public function clearCart();

    /**
     * @return int
     */
    public function getItemCount();

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItem(ItemTransfer $itemTransfer);

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItem(ItemTransfer $itemTransfer);

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function changeItemQuantity(ItemTransfer $itemTransfer, $quantity = 1);

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseItemQuantity(ItemTransfer $itemTransfer, $quantity = 1);

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseItemQuantity(ItemTransfer $itemTransfer, $quantity = 1);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function storeQuoteToSession(QuoteTransfer $quoteTransfer);

}
