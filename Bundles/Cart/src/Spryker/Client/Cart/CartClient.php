<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Cart\Session\QuoteSessionInterface;
use Spryker\Client\Cart\Zed\CartStubInterface;
use Spryker\Client\Cart\Exception\CartItemNotFoundException;

/**
 * @method \Spryker\Client\Cart\CartFactory getFactory()
 */
class CartClient extends AbstractClient implements CartClientInterface
{

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        return $this->getSession()->getQuote();
    }

    /**
     * @return void
     */
    public function clearCart()
    {
        $this->getSession()->clearQuote();
    }

    /**
     * @return int
     */
    public function getItemCount()
    {
        return $this->getSession()->getItemCount();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function storeQuoteToSession(QuoteTransfer $quoteTransfer)
    {
        $this->getSession()->setQuote($quoteTransfer);
    }

    /**
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
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItem(ItemTransfer $itemTransfer)
    {
        $itemTransfer = $this->mergeCartItems($itemTransfer, $this->findItem($itemTransfer));
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
