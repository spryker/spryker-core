<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Replacer;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartItemReplaceTransfer;
use Generated\Shared\Transfer\ItemReplaceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\Cart\CartChangeRequestExpander\CartChangeRequestExpanderInterface;
use Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface;
use Spryker\Client\Cart\Zed\CartStubInterface;
use Spryker\Client\CartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface;

class CartItemReplacer implements CartItemReplacerInterface
{
    /**
     * @var \Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Client\Cart\Zed\CartStubInterface
     */
    protected $cartStub;

    /**
     * @var \Spryker\Client\Cart\CartChangeRequestExpander\CartChangeRequestExpanderInterface
     */
    protected $cartChangeRequestExpander;

    /**
     * @var \Spryker\Client\CartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface
     */
    protected $quoteItemFinderPlugin;

    /**
     * @param \Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface $quoteClient
     * @param \Spryker\Client\Cart\Zed\CartStubInterface $cartStub
     * @param \Spryker\Client\Cart\CartChangeRequestExpander\CartChangeRequestExpanderInterface $cartChangeRequestExpander
     * @param \Spryker\Client\CartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface $quoteItemFinderPlugin
     */
    public function __construct(
        CartToQuoteInterface $quoteClient,
        CartStubInterface $cartStub,
        CartChangeRequestExpanderInterface $cartChangeRequestExpander,
        QuoteItemFinderPluginInterface $quoteItemFinderPlugin
    ) {
        $this->quoteClient = $quoteClient;
        $this->cartStub = $cartStub;
        $this->cartChangeRequestExpander = $cartChangeRequestExpander;
        $this->quoteItemFinderPlugin = $quoteItemFinderPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemReplaceTransfer $itemReplaceTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replaceItem(ItemReplaceTransfer $itemReplaceTransfer): QuoteResponseTransfer
    {
        $cartChangeTransferForRemoval = $this->prepareCartChangeTransferForRemoval($itemReplaceTransfer);
        $cartChangeTransferForAdding = $this->prepareCartChangeTransferForAdding($itemReplaceTransfer);

        $quoteResponseTransfer = $this->executeReplaceItem($cartChangeTransferForRemoval, $cartChangeTransferForAdding);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return (new QuoteResponseTransfer())
                ->setQuoteTransfer($this->quoteClient->getQuote())
                ->setIsSuccessful(false);
        }

        $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransferForRemoval
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransferForAdding
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function executeReplaceItem(
        CartChangeTransfer $cartChangeTransferForRemoval,
        CartChangeTransfer $cartChangeTransferForAdding
    ): QuoteResponseTransfer {
        $quoteResponseTransfer = (new QuoteResponseTransfer())
            ->setQuoteTransfer($this->quoteClient->getQuote())
            ->setIsSuccessful(false);

        if (!$cartChangeTransferForAdding->getItems()->count() || !$cartChangeTransferForRemoval->getItems()->count()) {
            return $quoteResponseTransfer;
        }

        return $this->cartStub->replaceItem((new CartItemReplaceTransfer())
            ->setCartChangeForRemoval($cartChangeTransferForRemoval)
            ->setCartChangeForAdding($cartChangeTransferForAdding));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemReplaceTransfer $itemReplaceTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function prepareCartChangeTransferForAdding(ItemReplaceTransfer $itemReplaceTransfer): CartChangeTransfer
    {
        $cartChangeTransferForAdding = $this->createCartChangeTransfer();
        $cartChangeTransferForAdding->addItem($itemReplaceTransfer->getNewItem());

        return $this->cartChangeRequestExpander->addItemsRequestExpand($cartChangeTransferForAdding);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemReplaceTransfer $itemReplaceTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function prepareCartChangeTransferForRemoval(ItemReplaceTransfer $itemReplaceTransfer): CartChangeTransfer
    {
        $cartChangeTransferForRemoval = $this->createCartChangeTransfer();

        $itemToBeReplaced = $itemReplaceTransfer->getItemToBeReplaced();
        $quoteItem = $this->findItem($itemToBeReplaced->getSku(), $itemToBeReplaced->getGroupKey());

        if (!$quoteItem) {
            return $cartChangeTransferForRemoval;
        }

        $cartChangeTransferForRemoval->addItem(clone $quoteItem);

        return $this->cartChangeRequestExpander->removeItemRequestExpand($cartChangeTransferForRemoval);
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer(): CartChangeTransfer
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        if (count($quoteTransfer->getItems()) === 0) {
            $quoteTransfer->setItems(new ArrayObject());
        }

        return (new CartChangeTransfer())
            ->setQuote($quoteTransfer);
    }

    /**
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItem(string $sku, ?string $groupKey = null): ?ItemTransfer
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        return $this->quoteItemFinderPlugin->findItem($quoteTransfer, $sku, $groupKey);
    }
}
