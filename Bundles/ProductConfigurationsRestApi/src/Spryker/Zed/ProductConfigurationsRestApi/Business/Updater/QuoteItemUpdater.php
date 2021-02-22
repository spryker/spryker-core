<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationsRestApi\Business\Updater;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentItemReplaceTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductConfigurationsRestApi\Business\Comparator\ItemComparatorInterface;
use Spryker\Zed\ProductConfigurationsRestApi\Dependency\Facade\ProductConfigurationsRestApiToPersistentCartFacadeInterface;

class QuoteItemUpdater implements QuoteItemUpdaterInterface
{
    /**
     * @uses \Spryker\Shared\CartsRestApi\CartsRestApiConfig::ERROR_IDENTIFIER_ITEM_NOT_FOUND
     */
    protected const ERROR_IDENTIFIER_ITEM_NOT_FOUND = 'ERROR_IDENTIFIER_ITEM_NOT_FOUND';

    /**
     * @var \Spryker\Zed\ProductConfigurationsRestApi\Business\Comparator\ItemComparatorInterface
     */
    protected $itemComparator;

    /**
     * @var \Spryker\Zed\ProductConfigurationsRestApi\Dependency\Facade\ProductConfigurationsRestApiToPersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @param \Spryker\Zed\ProductConfigurationsRestApi\Business\Comparator\ItemComparatorInterface $itemComparator
     * @param \Spryker\Zed\ProductConfigurationsRestApi\Dependency\Facade\ProductConfigurationsRestApiToPersistentCartFacadeInterface $persistentCartFacade
     */
    public function __construct(
        ItemComparatorInterface $itemComparator,
        ProductConfigurationsRestApiToPersistentCartFacadeInterface $persistentCartFacade
    ) {
        $this->itemComparator = $itemComparator;
        $this->persistentCartFacade = $persistentCartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuoteItem(
        CartItemRequestTransfer $cartItemRequestTransfer,
        QuoteResponseTransfer $quoteResponseTransfer
    ): QuoteResponseTransfer {
        $quoteTransfer = $quoteResponseTransfer->getQuoteTransferOrFail();

        $itemToBeReplaced = $this->extractQuoteItemToBeReplaced($quoteTransfer, $cartItemRequestTransfer);
        if (!$itemToBeReplaced) {
            return $quoteResponseTransfer->addError(
                (new QuoteErrorTransfer())->setErrorIdentifier(static::ERROR_IDENTIFIER_ITEM_NOT_FOUND)
            );
        }

        $newItemTransfer = (new ItemTransfer())->fromArray($cartItemRequestTransfer->toArray(), true);

        $persistentItemReplaceTransfer = $this->createPersistentItemReplaceTransfer(
            $quoteTransfer,
            $itemToBeReplaced,
            $newItemTransfer
        );

        return $this->persistentCartFacade->replaceItem($persistentItemReplaceTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function extractQuoteItemToBeReplaced(QuoteTransfer $quoteTransfer, CartItemRequestTransfer $cartItemRequestTransfer): ?ItemTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->itemComparator->isSameItem($itemTransfer, $cartItemRequestTransfer)) {
                return $itemTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemToBeReplaced
     * @param \Generated\Shared\Transfer\ItemTransfer $newItemTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentItemReplaceTransfer
     */
    protected function createPersistentItemReplaceTransfer(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemToBeReplaced,
        ItemTransfer $newItemTransfer
    ): PersistentItemReplaceTransfer {
        return (new PersistentItemReplaceTransfer())
            ->setQuote($quoteTransfer)
            ->setIdQuote($quoteTransfer->getIdQuote())
            ->setItemToBeReplaced($itemToBeReplaced)
            ->setNewItem($newItemTransfer)
            ->setCustomer($quoteTransfer->getCustomerOrFail());
    }
}
