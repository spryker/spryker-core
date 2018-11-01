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

class QuoteChangeObserver implements QuoteChangeObserverInterface
{
    public const CART_SYNCHRONIZE_ITEMS_PRICE_CHANGED = 'cart.validate.items.price.changed';

    /**
     * @var \Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @var array|\Spryker\Zed\CartExtension\Dependency\Plugin\QuoteChangeObserverPluginInterface[]
     */
    protected $quoteChangeObserverPlugins;

    /**
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface $messengerFacade
     * @param \Spryker\Zed\CartExtension\Dependency\Plugin\QuoteChangeObserverPluginInterface[] $quoteChangeObserverPlugins
     */
    public function __construct(CartToMessengerInterface $messengerFacade, array $quoteChangeObserverPlugins)
    {
        $this->messengerFacade = $messengerFacade;
        $this->quoteChangeObserverPlugins = $quoteChangeObserverPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return void
     */
    public function checkChanges(QuoteTransfer $resultQuoteTransfer, QuoteTransfer $sourceQuoteTransfer): void
    {
        $this->checkItemsChanges($resultQuoteTransfer, $sourceQuoteTransfer);
        $this->runQuoteChangeObserverPlugins($resultQuoteTransfer, $sourceQuoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return void
     */
    protected function checkItemsChanges(QuoteTransfer $resultQuoteTransfer, QuoteTransfer $sourceQuoteTransfer): void
    {
        $resultQuoteItemIndex = $this->createQuoteItemIndex($resultQuoteTransfer->getItems());
        $sourceQuoteItemIndex = $this->createQuoteItemIndex($sourceQuoteTransfer->getItems());

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

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return void
     */
    protected function runQuoteChangeObserverPlugins(QuoteTransfer $resultQuoteTransfer, QuoteTransfer $sourceQuoteTransfer): void
    {
        foreach ($this->quoteChangeObserverPlugins as $quoteChangeObserverPlugin) {
            $quoteChangeObserverPlugin->checkChanges($resultQuoteTransfer, $sourceQuoteTransfer);
        }
    }
}
