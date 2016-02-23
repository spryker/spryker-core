<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Cart\Exception\CartItemNotFoundException;

/**
 * @method \Spryker\Client\Cart\CartFactory getFactory()
 */
class CartClient extends AbstractClient implements CartClientInterface
{

    /**
     * Returns the stored quote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        return $this->getSession()->getQuote();
    }

    /**
     * Resets all data which is stored in the quote
     *
     * TODO FW Wrong name
     * @return void
     */
    public function clearCart()
    {
        $this->getSession()->clearQuote();
    }

    /**
     * Returns number of items in quote
     *
     * @return int
     */
    public function getItemCount()
    {
        return $this->getSession()->getItemCount();
    }

    /**
     * Stores quote
     *
     * TODO FW Please remove the "toSession" from the methodname as this is an implementation detail
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function storeQuoteToSession(QuoteTransfer $quoteTransfer)
    {
        $this->getSession()->setQuote($quoteTransfer);
    }

    /**
     * Adds an item (identfied by SKU and quantity) to the quote and stores it
     *
     * TODO FW This method does two things. Please remove the storeQuoteToSession() from here and adjust the description.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItem(ItemTransfer $itemTransfer)
    {
        $cartChangeTransfer = $this->prepareCartChangeTransfer($itemTransfer);
        $quoteTransfer = $this->getZedStub()->addItem($cartChangeTransfer);

        $this->storeQuoteToSession($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * Removes the item with the given SKU
     *
     * TODO FW The method only needs the SKU. All other information from the itemTransfer (incl the quantity) are dismissed. Why not just have the SKU as a parameter?
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItem(ItemTransfer $itemTransfer)
    {
        $itemTransfer = $this->mergeCartItems($itemTransfer, $this->findItem($itemTransfer)); // TODO FW Two things on one line
        $cartChangeTransfer = $this->prepareCartChangeTransfer($itemTransfer);
        $quoteTransfer = $this->getZedStub()->removeItem($cartChangeTransfer);

        $this->storeQuoteToSession($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemToFind
     *
     * @throws \Spryker\Client\Cart\Exception\CartItemNotFoundException
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function findItem(ItemTransfer $itemToFind)
    {
        $quoteTransfer = $this->getQuote();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() === $itemToFind->getSku()) {
                return $itemTransfer;
            }
        }

        throw new CartItemNotFoundException(
            sprintf('No item with sku "%s" found in cart.', $itemToFind->getSku())
        );
    }

    /**
     * Changes the quantity of the given item in the quote. If the quantity is equal to 0, the item
     * is removed from the quote.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function changeItemQuantity(ItemTransfer $itemTransfer, $quantity = 1)
    {
        if ($quantity === 0) {
            return $this->removeItem($itemTransfer);
        }

        $itemTransfer = clone $this->findItem($itemTransfer);
        $delta = abs($itemTransfer->getQuantity() - $quantity);

        if ($delta === 0) {
            return $this->getSession()->getQuote();
        }

        if ($itemTransfer->getQuantity() > $quantity) {
            return $this->decreaseItemQuantity($itemTransfer, $delta);
        }

        return $this->increaseItemQuantity($itemTransfer, $delta);
    }

    /**
     * Decreases the quantity of the given item in the quote.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseItemQuantity(ItemTransfer $itemTransfer, $quantity = 1)
    {
        $cartChangeTransfer = $this->createChangeTransferWithAdjustedQuantity($itemTransfer, $quantity);
        $quoteTransfer = $this->getZedStub()->decreaseItemQuantity($cartChangeTransfer);

        $this->storeQuoteToSession($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * Increases the quantity of the given item in the quote.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseItemQuantity(ItemTransfer $itemTransfer, $quantity = 1)
    {
        $cartChangeTransfer = $this->createChangeTransferWithAdjustedQuantity($itemTransfer, $quantity);
        $quoteTransfer = $this->getZedStub()->increaseItemQuantity($cartChangeTransfer);

        $this->storeQuoteToSession($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer()
    {
        $quoteTransfer = $this->getSession()->getQuote();
        $items = $quoteTransfer->getItems();

        if (count($items) === 0) {
            $quoteTransfer->setItems(new \ArrayObject());
        }

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote($quoteTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function prepareCartChangeTransfer(ItemTransfer $itemTransfer)
    {
        $cartChangeTransfer = $this->createCartChangeTransfer();
        $cartChangeTransfer->addItem($itemTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createChangeTransferWithAdjustedQuantity(ItemTransfer $itemTransfer, $quantity)
    {
        $itemTransfer = $this->mergeCartItems($itemTransfer, $this->findItem($itemTransfer));
        $itemTransfer->setQuantity($quantity);

        return $this->prepareCartChangeTransfer($itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $newItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $oldItemByIdentifier
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function mergeCartItems(ItemTransfer $newItemTransfer, ItemTransfer $oldItemByIdentifier)
    {
        $newItemTransfer->fromArray($oldItemByIdentifier->toArray(), true);

        return $newItemTransfer;
    }

    /**
     * @return \Spryker\Client\Cart\Zed\CartStubInterface
     */
    protected function getZedStub()
    {
        return $this->getFactory()->createZedStub();
    }

    /**
     * @return \Spryker\Client\Cart\Session\QuoteSessionInterface
     */
    protected function getSession()
    {
        return $this->getFactory()->createSession();
    }

}
