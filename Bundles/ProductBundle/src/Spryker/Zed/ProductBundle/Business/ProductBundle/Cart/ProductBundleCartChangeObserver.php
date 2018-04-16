<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Cart;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToMessengerFacadeInterface;

class ProductBundleCartChangeObserver implements ProductBundleCartChangeObserverInterface
{
    public const CART_SYNCHRONIZE_ITEMS_PRICE_CHANGED = 'cart.validate.items.price.changed';

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(ProductBundleToMessengerFacadeInterface $messengerFacade)
    {
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return void
     */
    public function checkBundleItemsChanges(QuoteTransfer $resultQuoteTransfer, QuoteTransfer $sourceQuoteTransfer): void
    {
        $resultQuoteItemIndex = $this->createQuoteItemIndex($resultQuoteTransfer->getBundleItems());
        $sourceQuoteItemIndex = $this->createQuoteItemIndex($sourceQuoteTransfer->getBundleItems());

        $this->checkQuoteItemPriceChanges($resultQuoteItemIndex, $sourceQuoteItemIndex);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $cartItems
     *
     * @return array
     */
    protected function createQuoteItemIndex(ArrayObject $cartItems): array
    {
        $cartIndex = [];
        foreach ($cartItems as $itemTransfer) {
            $itemIdentifier = $this->getItemIdentifier($itemTransfer);
            $cartIndex[$itemIdentifier] = [
                'sku' => $itemTransfer->getSku(),
                'price' => $itemTransfer->getUnitPrice(),
            ];
        }

        return $cartIndex;
    }

    /**
     * @param array $resultQuoteItemIndex
     * @param array $sourceQuoteItemIndex
     *
     * @return void
     */
    protected function checkQuoteItemPriceChanges(array $resultQuoteItemIndex, array $sourceQuoteItemIndex): void
    {
        $quoteItemDiff = $this->getQuoteItemsPriceDiff($resultQuoteItemIndex, $sourceQuoteItemIndex);
        if (!empty($quoteItemDiff)) {
            $this->messengerFacade->addInfoMessage(
                $this->createMessengerMessageTransfer(static::CART_SYNCHRONIZE_ITEMS_PRICE_CHANGED, [
                    '%sku%' => implode(', ', $quoteItemDiff),
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
    protected function getQuoteItemsPriceDiff(array $resultQuoteItemIndex, array $sourceQuoteItemIndex): array
    {
        $quoteItemDiff = [];
        foreach ($resultQuoteItemIndex as $key => $value) {
            $oldPrice = $sourceQuoteItemIndex[$key]['price'] ?? 0;
            if (isset($sourceQuoteItemIndex[$key]) && $value['price'] != $oldPrice) {
                $quoteItemDiff[] = $value['sku'];
            }
        }

        return $quoteItemDiff;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getItemIdentifier(ItemTransfer $itemTransfer): string
    {
        return $itemTransfer->getGroupKey() ?: $itemTransfer->getSku();
    }

    /**
     * @param string $message
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessengerMessageTransfer($message, array $parameters = []): MessageTransfer
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer
            ->setValue($message)
            ->setParameters($parameters);

        return $messageTransfer;
    }
}
