<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Cart\CartFactory getFactory()
 */
class CartClient extends AbstractClient implements CartClientInterface
{

    /**
     * Returns the stored quote
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        return $this->getQuoteClient()->getQuote();
    }

    /**
     * Resets all data which is stored in the quote
     *
     * @api
     *
     * @return void
     */
    public function clearQuote()
    {
        $this->getQuoteClient()->clearQuote();
    }

    /**
     * @api
     *
     * @return int
     */
    public function getItemCount()
    {
        return $this->getItemCounter()->getItemCount($this->getQuote());
    }

    /**
     * @return \Spryker\Client\Cart\Dependency\Plugin\ItemCountPluginInterface
     */
    protected function getItemCounter()
    {
        return $this->getFactory()->getItemCounter();
    }

    /**
     * Stores quote
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function storeQuote(QuoteTransfer $quoteTransfer)
    {
        $this->getQuoteClient()->setQuote($quoteTransfer);
    }

    /**
     * Adds an item (identified by SKU and quantity) makes zed request, stored cart into persistant store if used.
     *
     * @api
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItems(array $itemTransfers)
    {
        $cartChangeTransfer = $this->createCartChangeTransfer();
        foreach ($itemTransfers as $itemTransfer) {
            $cartChangeTransfer->addItem($itemTransfer);
        }

        return $this->getZedStub()->addItem($cartChangeTransfer);
    }

    /**
     * Removes the item with the given SKU
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItem($sku, $groupKey = null)
    {
        $itemTransfer = $this->findItem($sku, $groupKey);
        if (!$itemTransfer) {
            return $this->getQuote();
        }

        $cartChangeTransfer = $this->prepareCartChangeTransfer($itemTransfer);

        return $this->getZedStub()->removeItem($cartChangeTransfer);
    }

    /**
     *
     * Specification:
     *  - Remove all given items
     *
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItems(ArrayObject $items)
    {
        $cartChangeTransfer = $this->createCartChangeTransfer();
        $cartChangeTransfer->setItems($items);

        return $this->getZedStub()->removeItem($cartChangeTransfer);
    }

    /**
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItem($sku, $groupKey = null)
    {
        $quoteTransfer = $this->getQuote();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (($itemTransfer->getSku() === $sku && $groupKey === null) ||
                $itemTransfer->getGroupKey() === $groupKey) {

                return $itemTransfer;
            }
        }

        return null;
    }

    /**
     * Changes the quantity of the given item in the quote. If the quantity is equal to 0, the item
     * is removed from the quote.
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function changeItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        if ($quantity === 0) {
            return $this->removeItem($sku, $groupKey);
        }

        $itemTransfer = $this->findItem($sku, $groupKey);
        if (!$itemTransfer) {
            return $this->getQuote();
        }

        $delta = abs($itemTransfer->getQuantity() - $quantity);

        if ($delta === 0) {
            return $this->getQuoteClient()->getQuote();
        }

        if ($itemTransfer->getQuantity() > $quantity) {
            return $this->decreaseItemQuantity($sku, $groupKey, $delta);
        }

        return $this->increaseItemQuantity($sku, $groupKey, $delta);
    }

    /**
     * Decreases the quantity of the given item in the quote.
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        $decreaseItemTransfer = $this->findItem($sku, $groupKey);
        if (!$decreaseItemTransfer) {
            return $this->getQuote();
        }

        $itemTransfer = clone $decreaseItemTransfer;
        $itemTransfer->setQuantity($quantity);

        $cartChangeTransfer = $this->prepareCartChangeTransfer($itemTransfer);

        return $this->getZedStub()->removeItem($cartChangeTransfer);
    }

    /**
     * Increases the quantity of the given item in the quote.
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        $increaseItemTransfer = $this->findItem($sku, $groupKey);
        if (!$increaseItemTransfer) {
            return $this->getQuote();
        }

        $itemTransfer = clone $increaseItemTransfer;
        $itemTransfer->setQuantity($quantity);

        $cartChangeTransfer = $this->prepareCartChangeTransfer($itemTransfer);

        return $this->getZedStub()->addItem($cartChangeTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer()
    {
        $quoteTransfer = $this->getQuoteClient()->getQuote();
        $items = $quoteTransfer->getItems();

        if (count($items) === 0) {
            $quoteTransfer->setItems(new ArrayObject());
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
     * @api
     *
     * @return \Spryker\Client\Cart\Zed\CartStubInterface
     */
    public function getZedStub()
    {
        return $this->getFactory()->createZedStub();
    }

    /**
     * @return \Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface
     */
    protected function getQuoteClient()
    {
        return $this->getFactory()->getQuoteClient();
    }

}
