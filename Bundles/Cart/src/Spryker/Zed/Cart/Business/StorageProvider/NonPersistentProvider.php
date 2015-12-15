<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Business\StorageProvider;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Cart\Business\Exception\InvalidQuantityExeption;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class NonPersistentProvider implements StorageProviderInterface
{

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseItems(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->addItems($cartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItems(CartChangeTransfer $cartChangeTransfer)
    {
        $existingItems = $cartChangeTransfer->getQuote()->getItems();
        foreach ($cartChangeTransfer->getItems() as $item) {
            $this->isValidQuantity($item);
            $existingItems->append($item);
        }

        return $cartChangeTransfer->getQuote();
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseItems(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->removeItems($cartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItems(CartChangeTransfer $cartChangeTransfer)
    {
        $existingItems = $cartChangeTransfer->getQuote()->getItems();
        $cartIndex = $this->createCartIndex($existingItems);

        foreach ($cartChangeTransfer->getItems() as $item) {
            $this->isValidQuantity($item);

            if (isset($cartIndex[$item->getGroupKey()])) {
                $this->decreaseExistingItem($existingItems, $cartIndex[$item->getGroupKey()], $item);
            } else {
                $this->decreaseBySku($existingItems, $item);
            }
        }

        return $cartChangeTransfer->getQuote();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $cartItems
     *
     * @return array
     */
    protected function createCartIndex(\ArrayObject $cartItems)
    {
        $cartIndex = [];
        foreach ($cartItems as $key => $cartItem) {
            if (!empty($cartItem->getGroupKey())) {
                $cartIndex[$cartItem->getGroupKey()] = $key;
            }
        }

        return $cartIndex;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $existingItems
     * @param int $index
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return void
     */
    protected function decreaseExistingItem($existingItems, $index, $item)
    {
        $existingItem = $existingItems[$index];
        $newQuantity = $existingItem->getQuantity() - $item->getQuantity();

        if ($newQuantity > 0) {
            $existingItem->setQuantity($newQuantity);
        } else {
            unset($existingItems[$index]);
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $existingItems
     * @param \Generated\Shared\Transfer\ItemTransfer $changedItem
     *
     * @return void
     */
    protected function decreaseBySku(\ArrayObject $existingItems, ItemTransfer $changedItem)
    {
        foreach ($existingItems as $key => $cartIndexItem) {
            if ($cartIndexItem->getSku() === $changedItem->getSku()) {
                $this->decreaseExistingItem($existingItems, $key, $changedItem);

                return;
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return bool
     */
    protected function isValidQuantity(ItemTransfer $item)
    {
        if ($item->getQuantity() < 1) {
            throw new InvalidQuantityExeption(
                sprintf(
                    'Could not change cart item "%d" with "%d" as value.',
                    $item->getSku(),
                    $item->getQuantity()
                )
            );
        }

        return true;
    }

}
