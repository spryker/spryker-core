<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business\StorageProvider;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Cart\Business\Exception\InvalidQuantityExeption;

class NonPersistentProvider implements StorageProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItems(CartChangeTransfer $cartChangeTransfer)
    {
        $existingItems = $cartChangeTransfer->getQuote()->getItems();
        $cartIndex = $this->createCartIndex($existingItems);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $this->isValidQuantity($itemTransfer);

            $itemIdentifier = $this->getItemIdentifier($itemTransfer);
            if (isset($cartIndex[$itemIdentifier])) {
                $this->increaseExistingItem($existingItems, $cartIndex[$itemIdentifier], $itemTransfer);
            } else {
                $existingItems->append($itemTransfer);
                $cartIndex = $this->createCartIndex($existingItems);
            }
        }

        return $cartChangeTransfer->getQuote();
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

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $this->isValidQuantity($itemTransfer);

            $itemIdentifier = $this->getItemIdentifier($itemTransfer);
            if (isset($cartIndex[$itemIdentifier])) {
                $this->decreaseExistingItem($existingItems, $itemIdentifier, $itemTransfer, $cartIndex);
            }
        }

        return $cartChangeTransfer->getQuote();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $cartItems
     *
     * @return array
     */
    protected function createCartIndex(ArrayObject $cartItems)
    {
        $cartIndex = [];
        foreach ($cartItems as $key => $itemTransfer) {
            $itemIdentifier = $this->getItemIdentifier($itemTransfer);
            $cartIndex[$itemIdentifier] = $key;
        }

        return $cartIndex;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getItemIdentifier(ItemTransfer $itemTransfer)
    {
        return $itemTransfer->getGroupKey() ? $itemTransfer->getGroupKey() : $itemTransfer->getSku();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $existingItems
     * @param string $itemIdentifier
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $cartIndex
     *
     * @return void
     */
    protected function decreaseExistingItem($existingItems, $itemIdentifier, $itemTransfer, array $cartIndex)
    {
        $existingItemTransfer = null;
        $itemIndex = null;
        foreach ($existingItems as $index => $currentItemTransfer) {
            if ($currentItemTransfer->getGroupKey() === $itemIdentifier) {
                $existingItemTransfer = $currentItemTransfer;
                $itemIndex = $index;
                break;
            }
        }

        if ($existingItemTransfer === null) {
            $itemIndex = $cartIndex[$itemIdentifier];
            $existingItemTransfer = $existingItems[$itemIndex];
        }

        $changedQuantity = $existingItemTransfer->getQuantity() - $itemTransfer->getQuantity();

        if ($changedQuantity > 0) {
            $existingItemTransfer->setQuantity($changedQuantity);
        } else {
            unset($existingItems[$itemIndex]);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $existingItems
     * @param int $index
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function increaseExistingItem($existingItems, $index, $itemTransfer)
    {
        $existingItemTransfer = $existingItems[$index];
        $changedQuantity = $existingItemTransfer->getQuantity() + $itemTransfer->getQuantity();

        $existingItemTransfer->setQuantity($changedQuantity);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @throws \Spryker\Zed\Cart\Business\Exception\InvalidQuantityExeption
     *
     * @return bool
     */
    protected function isValidQuantity(ItemTransfer $itemTransfer)
    {
        if ($itemTransfer->getQuantity() < 1) {
            throw new InvalidQuantityExeption(
                sprintf(
                    'Could not change cart item "%d" with "%d" as value.',
                    $itemTransfer->getSku(),
                    $itemTransfer->getQuantity()
                )
            );
        }

        return true;
    }
}
