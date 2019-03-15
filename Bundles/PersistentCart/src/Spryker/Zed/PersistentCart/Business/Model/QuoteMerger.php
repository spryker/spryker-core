<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteMergeRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PersistentCart\Dependency\Service\PersistentCartToUtilQuantityServiceInterface;
use Traversable;

class QuoteMerger implements QuoteMergerInterface
{
    /**
     * @var \Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationStrategyPluginInterface[]
     */
    protected $cartAddItemStrategyPlugins;

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Service\PersistentCartToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationStrategyPluginInterface[] $cartAddItemStrategyPlugins
     * @param \Spryker\Zed\PersistentCart\Dependency\Service\PersistentCartToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(
        array $cartAddItemStrategyPlugins,
        PersistentCartToUtilQuantityServiceInterface $utilQuantityService
    ) {
        $this->cartAddItemStrategyPlugins = $cartAddItemStrategyPlugins;
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteMergeRequestTransfer $quoteMergeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function merge(QuoteMergeRequestTransfer $quoteMergeRequestTransfer): QuoteTransfer
    {
        $targetQuote = clone $quoteMergeRequestTransfer->getTargetQuote();
        $targetQuote = $this->mergeItems($targetQuote, $quoteMergeRequestTransfer->getSourceQuote());

        return $targetQuote;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $targetQuote
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mergeItems(QuoteTransfer $targetQuote, QuoteTransfer $sourceQuote): QuoteTransfer
    {
        foreach ($sourceQuote->getItems() as $itemTransfer) {
            $this->addItem($itemTransfer, $targetQuote);
        }

        return $targetQuote;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addItem(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): void
    {
        foreach ($this->cartAddItemStrategyPlugins as $quoteMergeItemStrategyPlugin) {
            if ($quoteMergeItemStrategyPlugin->isApplicable($itemTransfer, $quoteTransfer)) {
                $quoteMergeItemStrategyPlugin->execute($itemTransfer, $quoteTransfer);

                return;
            }
        }

        $existingItems = $quoteTransfer->getItems();
        $cartIndex = $this->createQuoteItemIndex($existingItems);
        $itemIdentifier = $this->getItemIdentifier($itemTransfer);
        if (isset($cartIndex[$itemIdentifier])) {
            $this->increaseExistingItem((array)$existingItems, $cartIndex[$itemIdentifier], $itemTransfer);

            return;
        }

        $existingItems->append($itemTransfer);
    }

    /**
     * @param \Traversable|\Generated\Shared\Transfer\ItemTransfer[] $cartItems
     *
     * @return array
     */
    protected function createQuoteItemIndex(Traversable $cartItems): array
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
    protected function getItemIdentifier(ItemTransfer $itemTransfer): string
    {
        return $itemTransfer->getGroupKey() ?: $itemTransfer->getSku();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $existingItems
     * @param int $index
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function increaseExistingItem(array $existingItems, int $index, ItemTransfer $itemTransfer): void
    {
        $existingItemTransfer = $existingItems[$index];
        $changedQuantity = $existingItemTransfer->getQuantity() + $itemTransfer->getQuantity();

        $existingItemTransfer->setQuantity($this->roundQuantity($changedQuantity));
    }

    /**
     * @param float $quantity
     *
     * @return float
     */
    protected function roundQuantity(float $quantity): float
    {
        return $this->utilQuantityService->roundQuantity($quantity);
    }
}
