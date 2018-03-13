<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface;

class QuoteChangeNote implements QuoteChangeNoteInterface
{
    const CART_VALIDATE_ITEMS_ADDED = 'cart.validate.items.added';
    const CART_SYNCHRONIZE_ITEMS_PRICE_CHANGED = 'cart.validate.items.price.changed';
    const CART_VALIDATE_DISCOUNT_ADDED = 'cart.validate.discount.added';
    const CART_VALIDATE_DISCOUNT_REMOVED = 'cart.validate.discount.removed';

    /**
     * @var \Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface $messengerFacade
     */
    public function __construct(CartToMessengerInterface $messengerFacade)
    {
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return void
     */
    public function checkChanges(QuoteTransfer $resultQuoteTransfer, QuoteTransfer $sourceQuoteTransfer)
    {
        $this->checkItemsChanges($resultQuoteTransfer, $sourceQuoteTransfer);
        $this->checkDiscountChanges($resultQuoteTransfer, $sourceQuoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return void
     */
    protected function checkDiscountChanges(QuoteTransfer $resultQuoteTransfer, QuoteTransfer $sourceQuoteTransfer)
    {
        $resultQuoteDiscountNumber = count($resultQuoteTransfer->getCartRuleDiscounts());
        $sourceQuoteDiscountNumber = count($sourceQuoteTransfer->getCartRuleDiscounts());
        if ($resultQuoteDiscountNumber === $sourceQuoteDiscountNumber) {
            return;
        }

        if ($resultQuoteDiscountNumber > $sourceQuoteDiscountNumber) {
            $this->messengerFacade->addInfoMessage(
                $this->createMessengerMessageTransfer(static::CART_VALIDATE_DISCOUNT_ADDED)
            );
        }

        $this->messengerFacade->addInfoMessage(
            $this->createMessengerMessageTransfer(static::CART_VALIDATE_DISCOUNT_REMOVED)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return void
     */
    protected function checkItemsChanges(QuoteTransfer $resultQuoteTransfer, QuoteTransfer $sourceQuoteTransfer)
    {
        $resultQuoteItemIndex = $this->createQuoteItemIndex($resultQuoteTransfer->getItems());
        $sourceQuoteItemIndex = $this->createQuoteItemIndex($sourceQuoteTransfer->getItems());

        $quoteItemDiff = $this->getQuoteItemsQuantityDiff($resultQuoteItemIndex, $sourceQuoteItemIndex);
        if (!empty($quoteItemDiff)) {
            $this->messengerFacade->addInfoMessage(
                $this->createMessengerMessageTransfer(static::CART_VALIDATE_ITEMS_ADDED, [
                    'sku' => implode(', ', $quoteItemDiff),
                ])
            );
        }

        $quoteItemDiff = $this->getQuoteItemsPriceDiff($resultQuoteItemIndex, $sourceQuoteItemIndex);
        if (!empty($quoteItemDiff)) {
            $this->messengerFacade->addInfoMessage(
                $this->createMessengerMessageTransfer(static::CART_SYNCHRONIZE_ITEMS_PRICE_CHANGED, [
                    'sku' => implode(', ', $quoteItemDiff),
                ])
            );
        }
    }

    /**
     * @param array $resultQuoteItemIndex
     * @param array $sourceQuoteItemIndex
     *
     * @return array
     */
    protected function getQuoteItemsQuantityDiff(array $resultQuoteItemIndex, array $sourceQuoteItemIndex)
    {
        $quoteItemDiff = [];
        foreach ($resultQuoteItemIndex as $key => $value) {
            $oldQuantity = $sourceQuoteItemIndex[$key]['quantity'] ?? 0;
            if ($value['quantity'] > $oldQuantity) {
                $quoteItemDiff[] = $value['sku'];
            }
        }

        return $quoteItemDiff;
    }

    /**
     * @param array $resultQuoteItemIndex
     * @param array $sourceQuoteItemIndex
     *
     * @return array
     */
    protected function getQuoteItemsPriceDiff(array $resultQuoteItemIndex, array $sourceQuoteItemIndex)
    {
        $quoteItemDiff = [];
        foreach ($resultQuoteItemIndex as $key => $value) {
            $oldPrice = $sourceQuoteItemIndex[$key]['price'] ?? 0;
            if ($value['price'] > $oldPrice) {
                $quoteItemDiff[] = $value['sku'];
            }
        }

        return $quoteItemDiff;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $cartItems
     *
     * @return array
     */
    protected function createQuoteItemIndex(ArrayObject $cartItems)
    {
        $cartIndex = [];
        foreach ($cartItems as $itemTransfer) {
            $itemIdentifier = $this->getItemIdentifier($itemTransfer);
            $cartIndex[$itemIdentifier] = [
                'sku' => $itemTransfer->getSku(),
                'quantity' => $itemTransfer->getQuantity(),
                'price' => $itemTransfer->getUnitPrice(),
            ];
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
        return $itemTransfer->getGroupKey() ?: $itemTransfer->getSku();
    }

    /**
     * @param string $message
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessengerMessageTransfer($message, array $parameters = [])
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($message);
        $messageTransfer->setParameters($parameters);

        return $messageTransfer;
    }
}
