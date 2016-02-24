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
     * @return void
     */
    public function clearQuote()
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function storeQuote(QuoteTransfer $quoteTransfer)
    {
        $this->getSession()->setQuote($quoteTransfer);
    }

    /**
     * Adds an item (identfied by SKU and quantity) makes zed request, stored cart into persistant store if used.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItem(ItemTransfer $itemTransfer)
    {
        $cartChangeTransfer = $this->prepareCartChangeTransfer($itemTransfer);
        return $this->getZedStub()->addItem($cartChangeTransfer);

    }

    /**
     * Removes the item with the given SKU
     *
     * @param string $sku
     * @param string $groupKey
     *
     * @throws \Spryker\Client\Cart\Exception\CartItemNotFoundException
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItem($sku, $groupKey = null)
    {
        $itemTransfer = $this->findItem($sku, $groupKey);
        $cartChangeTransfer = $this->prepareCartChangeTransfer($itemTransfer);
        return $this->getZedStub()->removeItem($cartChangeTransfer);
    }

    /**
     *
     * @param string $sku
     * @param string $groupKey
     *
     * @throws \Spryker\Client\Cart\Exception\CartItemNotFoundException
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     *
     */
    protected function findItem($sku, $groupKey = null)
    {
        $quoteTransfer = $this->getQuote();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() === $sku || ($groupKey !== null && $itemTransfer->getGroupKey() === $groupKey)) {
                return $itemTransfer;
            }
        }

        throw new CartItemNotFoundException(
            sprintf('No item with sku "%s" found in cart.', $sku)
        );
    }

    /**
     * Changes the quantity of the given item in the quote. If the quantity is equal to 0, the item
     * is removed from the quote.
     *
     * @param string $sku
     * @param string $groupKey
     * @param int $quantity
     *
     * @throws \Spryker\Client\Cart\Exception\CartItemNotFoundException
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     *
     */
    public function changeItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        if ($quantity === 0) {
            return $this->removeItem($sku, $groupKey);
        }

        $itemTransfer = $this->findItem($sku, $groupKey);
        $delta = abs($itemTransfer->getQuantity() - $quantity);

        if ($delta === 0) {
            return $this->getSession()->getQuote();
        }

        if ($itemTransfer->getQuantity() > $quantity) {
            return $this->decreaseItemQuantity($sku, $groupKey, $delta);
        }

        return $this->increaseItemQuantity($sku, $groupKey, $delta);
    }

    /**
     * Decreases the quantity of the given item in the quote.
     *
     * @param string $sku
     * @param string $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        $itemTransfer = clone $this->findItem($sku, $groupKey);
        $itemTransfer->setQuantity($quantity);

        $cartChangeTransfer = $this->prepareCartChangeTransfer($itemTransfer);
        return $this->getZedStub()->removeItem($cartChangeTransfer);
    }

    /**
     * Increases the quantity of the given item in the quote.
     *
     * @param string$sku
     * @param string $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        $itemTransfer = clone $this->findItem($sku, $groupKey);
        $itemTransfer->setQuantity($quantity);

        $cartChangeTransfer = $this->prepareCartChangeTransfer($itemTransfer);
        return $this->getZedStub()->addItem($cartChangeTransfer);
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
