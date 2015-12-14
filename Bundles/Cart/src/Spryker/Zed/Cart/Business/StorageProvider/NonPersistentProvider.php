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
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return QuoteTransfer
     */
    public function increaseItems(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->addItems($cartChangeTransfer);
    }

    /**
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return QuoteTransfer
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
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return QuoteTransfer
     */
    public function decreaseItems(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->removeItems($cartChangeTransfer);
    }

    /**
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return QuoteTransfer
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
     * @param \ArrayObject|ItemTransfer[] $cartItems
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
     * @param ItemTransfer[] $existingItems
     * @param int $index
     * @param ItemTransfer $item
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
     * @param \ArrayObject|ItemTransfer[] $existingItems
     * @param ItemTransfer $changedItem
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
     * @param ItemTransfer $item
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
