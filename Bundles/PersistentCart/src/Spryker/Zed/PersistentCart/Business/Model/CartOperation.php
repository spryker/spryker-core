<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface;
use Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface;

class CartOperation implements CartOperationInterface
{
    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface
     */
    protected $quoteResponseExpander;

    /**
     * @var \Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface
     */
    protected $itemFinderPlugin;

    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteResolverInterface
     */
    protected $quoteResolver;

    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperationInterface
     */
    protected $quoteItemOperation;

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface $itemFinderPlugin
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface $quoteResponseExpander
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteResolverInterface $quoteResolver
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperationInterface $quoteItemOperations
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(
        QuoteItemFinderPluginInterface $itemFinderPlugin,
        QuoteResponseExpanderInterface $quoteResponseExpander,
        QuoteResolverInterface $quoteResolver,
        QuoteItemOperationInterface $quoteItemOperations,
        PersistentCartToQuoteFacadeInterface $quoteFacade
    ) {
        $this->quoteResponseExpander = $quoteResponseExpander;
        $this->itemFinderPlugin = $itemFinderPlugin;
        $this->quoteResolver = $quoteResolver;
        $this->quoteItemOperation = $quoteItemOperations;
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function add(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        $persistentCartChangeTransfer->requireCustomer();

        $quoteResponseTransfer = $this->quoteResolver->resolveCustomerQuote(
            (int)$persistentCartChangeTransfer->getIdQuote(),
            $persistentCartChangeTransfer->getCustomer(),
            $persistentCartChangeTransfer->getQuoteUpdateRequestAttributes()
        );
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $quoteTransfer = $this->mergeQuotes(
            $quoteResponseTransfer->getQuoteTransfer(),
            $persistentCartChangeTransfer->getQuote()
        );

        return $this->quoteItemOperation->addItems((array)$persistentCartChangeTransfer->getItems(), $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addValid(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        $persistentCartChangeTransfer->requireCustomer();

        $quoteResponseTransfer = $this->quoteResolver->resolveCustomerQuote(
            (int)$persistentCartChangeTransfer->getIdQuote(),
            $persistentCartChangeTransfer->getCustomer(),
            $persistentCartChangeTransfer->getQuoteUpdateRequestAttributes()
        );
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $quoteTransfer = $this->mergeQuotes(
            $quoteResponseTransfer->getQuoteTransfer(),
            $persistentCartChangeTransfer->getQuote()
        );

        return $this->quoteItemOperation->addValidItems((array)$persistentCartChangeTransfer->getItems(), $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function remove(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        $persistentCartChangeTransfer->requireCustomer();

        $quoteResponseTransfer = $this->quoteResolver->resolveCustomerQuote(
            $persistentCartChangeTransfer->getIdQuote(),
            $persistentCartChangeTransfer->getCustomer()
        );
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $quoteTransfer = $this->mergeQuotes(
            $quoteResponseTransfer->getQuoteTransfer(),
            $persistentCartChangeTransfer->getQuote()
        );

        $itemTransferList = [];
        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            $itemTransferList[] = $this->findItemInQuote($itemTransfer, $quoteTransfer);
        }

        return $this->quoteItemOperation->removeItems($itemTransferList, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function changeItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer
    {
        $persistentCartChangeQuantityTransfer->requireCustomer();

        $quoteResponseTransfer = $this->quoteResolver->resolveCustomerQuote(
            $persistentCartChangeQuantityTransfer->getIdQuote(),
            $persistentCartChangeQuantityTransfer->getCustomer()
        );
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $quoteTransfer = $this->mergeQuotes(
            $quoteResponseTransfer->getQuoteTransfer(),
            $persistentCartChangeQuantityTransfer->getQuote()
        );

        $itemTransfer = $persistentCartChangeQuantityTransfer->getItem();
        $quoteItemTransfer = $this->findItemInQuote($itemTransfer, $quoteTransfer);
        if (!$quoteItemTransfer) {
            $quoteResponseTransfer = new QuoteResponseTransfer();
            $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
            $quoteResponseTransfer->setIsSuccessful(false);

            return $quoteResponseTransfer;
        }
        if ($itemTransfer->getQuantity() === 0) {
            return $this->quoteItemOperation->removeItems([$quoteItemTransfer], $quoteTransfer);
        }

        $delta = abs($quoteItemTransfer->getQuantity() - $itemTransfer->getQuantity());
        if ($delta === 0) {
            $quoteResponseTransfer = new QuoteResponseTransfer();
            $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
            $quoteResponseTransfer->setIsSuccessful(false);

            return $quoteResponseTransfer;
        }

        $changeItemTransfer = clone $quoteItemTransfer;
        $changeItemTransfer->setQuantity($delta);
        if ($quoteItemTransfer->getQuantity() > $itemTransfer->getQuantity()) {
            return $this->quoteItemOperation->removeItems([$changeItemTransfer], $quoteTransfer);
        }

        return $this->quoteItemOperation->addItems([$changeItemTransfer], $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuantity(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        $persistentCartChangeTransfer->requireCustomer();

        $quoteResponseTransfer = $this->quoteResolver->resolveCustomerQuote(
            $persistentCartChangeTransfer->getIdQuote(),
            $persistentCartChangeTransfer->getCustomer()
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $quoteTransfer = $this->mergeQuotes(
            $quoteResponseTransfer->getQuoteTransfer(),
            $persistentCartChangeTransfer->getQuote()
        );

        $itemsToAdd = $this->prepareItemsForAdd($persistentCartChangeTransfer, $quoteTransfer);

        if ($itemsToAdd) {
            $quoteResponseTransfer = $this->quoteItemOperation->addItems($itemsToAdd, $quoteTransfer);
        }

        $itemsToRemove = $this->prepareItemsForRemoval($persistentCartChangeTransfer, $quoteTransfer);

        if ($quoteResponseTransfer->getIsSuccessful() && $itemsToRemove) {
            $quoteResponseTransfer = $this->quoteItemOperation->removeItems($itemsToRemove, $quoteTransfer);

            // TODO: if we have isSuccessful = false we should revert original quote.
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function prepareItemsForAdd(PersistentCartChangeTransfer $persistentCartChangeTransfer, QuoteTransfer $quoteTransfer): array
    {
        $itemsToAdd = [];

        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            $quoteItemTransfer = $this->findItemInQuote($itemTransfer, $quoteTransfer);

            if (!$quoteItemTransfer || $itemTransfer->getQuantity() === 0) {
                continue;
            }

            $delta = abs($quoteItemTransfer->getQuantity() - $itemTransfer->getQuantity());

            if ($delta === 0) {
                continue;
            }

            $changeItemTransfer = clone $quoteItemTransfer;
            $changeItemTransfer->setQuantity($delta);

            if ($quoteItemTransfer->getQuantity() <= $itemTransfer->getQuantity()) {
                $itemsToAdd[] = $changeItemTransfer;
            }
        }

        return $itemsToAdd;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function prepareItemsForRemoval(PersistentCartChangeTransfer $persistentCartChangeTransfer, QuoteTransfer $quoteTransfer): array
    {
        $itemsToRemove = [];

        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            $quoteItemTransfer = $this->findItemInQuote($itemTransfer, $quoteTransfer);

            if (!$quoteItemTransfer) {
                continue;
            }

            if ($itemTransfer->getQuantity() === 0) {
                $itemsToRemove[] = $quoteItemTransfer;
                continue;
            }

            $delta = abs($quoteItemTransfer->getQuantity() - $itemTransfer->getQuantity());

            if ($delta === 0) {
                continue;
            }

            $changeItemTransfer = clone $quoteItemTransfer;
            $changeItemTransfer->setQuantity($delta);

            if ($quoteItemTransfer->getQuantity() > $itemTransfer->getQuantity()) {
                $itemsToRemove[] = $changeItemTransfer;
            }
        }

        return $itemsToRemove;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function decreaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer
    {
        $persistentCartChangeQuantityTransfer->requireCustomer();

        $quoteResponseTransfer = $this->quoteResolver->resolveCustomerQuote(
            $persistentCartChangeQuantityTransfer->getIdQuote(),
            $persistentCartChangeQuantityTransfer->getCustomer()
        );
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $quoteTransfer = $this->mergeQuotes(
            $quoteResponseTransfer->getQuoteTransfer(),
            $persistentCartChangeQuantityTransfer->getQuote()
        );

        $decreaseItemTransfer = $this->findItemInQuote($persistentCartChangeQuantityTransfer->getItem(), $quoteTransfer);
        if (!$decreaseItemTransfer || !$persistentCartChangeQuantityTransfer->getItem()->getQuantity()) {
            return $quoteResponseTransfer;
        }

        $itemTransfer = clone $decreaseItemTransfer;
        $itemTransfer->setQuantity(
            $persistentCartChangeQuantityTransfer->getItem()->getQuantity()
        );

        return $this->quoteItemOperation->removeItems([$itemTransfer], $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function increaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer
    {
        $persistentCartChangeQuantityTransfer->requireCustomer();

        $quoteResponseTransfer = $this->quoteResolver->resolveCustomerQuote(
            $persistentCartChangeQuantityTransfer->getIdQuote(),
            $persistentCartChangeQuantityTransfer->getCustomer()
        );
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $quoteTransfer = $this->mergeQuotes(
            $quoteResponseTransfer->getQuoteTransfer(),
            $persistentCartChangeQuantityTransfer->getQuote()
        );

        $decreaseItemTransfer = $this->findItemInQuote($persistentCartChangeQuantityTransfer->getItem(), $quoteTransfer);
        if (!$decreaseItemTransfer || !$persistentCartChangeQuantityTransfer->getItem()->getQuantity()) {
            return $this->createQuoteItemNotFoundResult($quoteTransfer, $persistentCartChangeQuantityTransfer->getCustomer());
        }

        $itemTransfer = clone $decreaseItemTransfer;
        $itemTransfer->setQuantity(
            $persistentCartChangeQuantityTransfer->getItem()->getQuantity()
        );

        return $this->quoteItemOperation->addItems([$itemTransfer], $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function reloadItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireCustomer();
        $quoteResponseTransfer = $this->quoteResolver->resolveCustomerQuote(
            $quoteTransfer->getIdQuote(),
            $quoteTransfer->getCustomer()
        );
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }
        $customerQuoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $quoteTransfer->fromArray($customerQuoteTransfer->modifiedToArray(), true);

        return $this->quoteItemOperation->reloadItems($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validate($quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireCustomer();
        $quoteResponseTransfer = $this->quoteResolver->resolveCustomerQuote(
            $quoteTransfer->getIdQuote(),
            $quoteTransfer->getCustomer()
        );
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }
        $customerQuoteTransfer = $quoteResponseTransfer->getQuoteTransfer();

        if ($this->quoteFacade->isQuoteLocked($customerQuoteTransfer)) {
            return $this->quoteResponseExpander->expand($quoteResponseTransfer);
        }

        $quoteTransfer->fromArray($customerQuoteTransfer->modifiedToArray(), true);

        return $this->quoteItemOperation->validate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $persistentQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mergeQuotes(QuoteTransfer $persistentQuoteTransfer, ?QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$quoteTransfer) {
            return $persistentQuoteTransfer;
        }

        $quoteTransfer->fromArray($persistentQuoteTransfer->toArray(), true);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItemInQuote(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): ?ItemTransfer
    {
        return $this->itemFinderPlugin->findItem($quoteTransfer, $itemTransfer->getSku(), $itemTransfer->getGroupKey());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createQuoteItemNotFoundResult(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setCustomer($customerTransfer);
        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
        $quoteResponseTransfer->setIsSuccessful(false);

        return $this->quoteResponseExpander->expand($quoteResponseTransfer);
    }
}
