<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Operation;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentItemReplaceTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperationInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResolverInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface;
use Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface;

class CartItemOperation implements CartItemOperationInterface
{
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
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteResolverInterface $quoteResolver
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperationInterface $quoteItemOperations
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(
        QuoteItemFinderPluginInterface $itemFinderPlugin,
        QuoteResolverInterface $quoteResolver,
        QuoteItemOperationInterface $quoteItemOperations,
        PersistentCartToQuoteFacadeInterface $quoteFacade
    ) {
        $this->itemFinderPlugin = $itemFinderPlugin;
        $this->quoteResolver = $quoteResolver;
        $this->quoteItemOperation = $quoteItemOperations;
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentItemReplaceTransfer $persistentItemReplaceTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replaceItem(PersistentItemReplaceTransfer $persistentItemReplaceTransfer): QuoteResponseTransfer
    {
        $persistentItemReplaceTransfer->requireCustomer();

        $persistentQuoteResponseTransfer = $this->quoteResolver->resolveCustomerQuote(
            $persistentItemReplaceTransfer->getIdQuote(),
            $persistentItemReplaceTransfer->getCustomer()
        );

        if (!$persistentQuoteResponseTransfer->getIsSuccessful()) {
            return $persistentQuoteResponseTransfer;
        }

        $quoteTransfer = $this->mergeQuotes(
            (new QuoteTransfer())->fromArray($persistentQuoteResponseTransfer->getQuoteTransfer()->toArray(), true),
            $persistentItemReplaceTransfer->getQuote()
        );

        $itemsToRemoval = $this->prepareItemsForRemoval($persistentItemReplaceTransfer, $quoteTransfer);
        $itemsToAdding = [$persistentItemReplaceTransfer->getNewItem()];

        $quoteResponseTransfer = $this->executeReplaceItem($itemsToRemoval, $itemsToAdding, $quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->quoteFacade
                ->updateQuote($persistentQuoteResponseTransfer->getQuoteTransfer())
                ->setIsSuccessful(false);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemsToRemoval
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemsToAdding
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function executeReplaceItem(
        array $itemsToRemoval,
        array $itemsToAdding,
        QuoteTransfer $quoteTransfer
    ): QuoteResponseTransfer {
        $quoteResponseTransfer = (new QuoteResponseTransfer())
            ->setIsSuccessful(false);

        if (!$itemsToAdding || !$itemsToRemoval) {
            return $quoteResponseTransfer;
        }

        $quoteResponseTransfer = $this->quoteItemOperation->removeItems($itemsToRemoval, $quoteTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            return $this->quoteItemOperation->addItems($itemsToAdding, $quoteResponseTransfer->getQuoteTransfer());
        }

        return $quoteResponseTransfer;
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

        $quoteTransfer->fromArray($persistentQuoteTransfer->modifiedToArray(), true);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentItemReplaceTransfer $persistentItemReplaceTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function prepareItemsForRemoval(PersistentItemReplaceTransfer $persistentItemReplaceTransfer, QuoteTransfer $quoteTransfer): array
    {
        $quoteItem = $this->findItemInQuote($persistentItemReplaceTransfer->getItemToBeReplaced(), $quoteTransfer);

        return $quoteItem ? [clone $quoteItem] : [];
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
}
