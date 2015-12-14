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
     * @return QuoteTransfer
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
     * @param ItemTransfer $itemTransfer
     *
     * @return QuoteTransfer
     */
    public function addItem(ItemTransfer $itemTransfer);

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return QuoteTransfer
     */
    public function removeItem(ItemTransfer $itemTransfer);

    /**
     * @param ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return QuoteTransfer
     */
    public function changeItemQuantity(ItemTransfer $itemTransfer, $quantity = 1);

    /**
     * @param ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return QuoteTransfer
     */
    public function decreaseItemQuantity(ItemTransfer $itemTransfer, $quantity = 1);

    /**
     * @param ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return QuoteTransfer
     */
    public function increaseItemQuantity(ItemTransfer $itemTransfer, $quantity = 1);

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function storeQuoteToSession(QuoteTransfer $quoteTransfer);

}
