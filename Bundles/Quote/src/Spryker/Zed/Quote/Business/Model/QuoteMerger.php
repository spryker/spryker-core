<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteMergeTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Quote\Dependency\Facade\QuoteToCalculationFacadeInterface;

class QuoteMerger implements QuoteMergerInterface
{
    /**
     * @var \Spryker\Zed\Quote\Business\Model\QuoteInterface
     */
    protected $quoteModel;

    /**
     * @var \Spryker\Zed\Quote\Dependency\Facade\QuoteToCalculationFacadeInterface
     */
    protected $calculationFacade;

    /**
     * @param \Spryker\Zed\Quote\Business\Model\QuoteInterface $quoteModel
     * @param \Spryker\Zed\Quote\Dependency\Facade\QuoteToCalculationFacadeInterface $calculationFacade
     */
    public function __construct(QuoteInterface $quoteModel, QuoteToCalculationFacadeInterface $calculationFacade)
    {
        $this->quoteModel = $quoteModel;
        $this->calculationFacade = $calculationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteMergeTransfer $quoteMergeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function merge(QuoteMergeTransfer $quoteMergeTransfer): QuoteResponseTransfer
    {
        $sourceQuote = $quoteMergeTransfer->getSourceQuote();
        $targetQuote = $quoteMergeTransfer->getTargetQuote();
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->fromArray($sourceQuote->modifiedToArray());
        $quoteTransfer->fromArray($targetQuote->modifiedToArray());
        $quoteTransfer = $this->mergeItems($quoteTransfer, $sourceQuote);
        $quoteTransfer = $this->calculationFacade->recalculateQuote($quoteTransfer);
        $quoteResponseTransfer = $this->quoteModel->save($quoteTransfer);
        $quoteTransfer->setIdQuote(
            $quoteResponseTransfer->getQuoteTransfer()->getIdQuote()
        );
        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $targetQuote
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mergeItems(QuoteTransfer $targetQuote, QuoteTransfer $sourceQuote)
    {
        $existingItems = $targetQuote->getItems();
        $cartIndex = $this->createQuoteItemIndex($existingItems);

        foreach ($sourceQuote->getItems() as $itemTransfer) {
            $itemIdentifier = $this->getItemIdentifier($itemTransfer);
            if (isset($cartIndex[$itemIdentifier])) {
                $this->increaseExistingItem($existingItems, $cartIndex[$itemIdentifier], $itemTransfer);
            } else {
                $existingItems->append($itemTransfer);
            }
        }

        return $targetQuote;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $cartItems
     *
     * @return array
     */
    protected function createQuoteItemIndex(ArrayObject $cartItems)
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
        return $itemTransfer->getGroupKey() ?: $itemTransfer->getSku();
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
}
