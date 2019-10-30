<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy;

use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToQuoteClientInterface;

class SessionQuoteStorageStrategy implements QuoteStorageStrategyInterface
{
    protected const STORAGE_STRATEGY = 'session';

    /**
     * @var \Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToQuoteClientInterface $quoteClient
     */
    public function __construct(ConfigurableBundleCartNoteToQuoteClientInterface $quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

    /**
     * @return string
     */
    public function getStorageStrategy(): string
    {
        return static::STORAGE_STRATEGY;
    }

    /**
     * @param string $cartNote
     * @param string $configurableBundleGroupKey
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setCartNoteToConfigurableBundle(string $cartNote, string $configurableBundleGroupKey): QuoteResponseTransfer
    {
        $quoteNoteResponseTransfer = new QuoteResponseTransfer();
        $quoteTransfer = $this->quoteClient->getQuote();
        $itemCollection = $this->findItemsByConfigurableBundleGroupKey($quoteTransfer, $configurableBundleGroupKey);

        if ($itemCollection->getItems()->count() === 0) {
            $quoteNoteResponseTransfer->setIsSuccessful(false);

            return $quoteNoteResponseTransfer;
        }

        foreach ($itemCollection->getItems() as $itemTransfer) {
            $itemTransfer->getConfiguredBundle()->setCartNote($cartNote);
        }

        $this->quoteClient->setQuote($quoteTransfer);
        $quoteNoteResponseTransfer->setIsSuccessful(true);

        return $quoteNoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $configurableBundleGroupKey
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    protected function findItemsByConfigurableBundleGroupKey(
        QuoteTransfer $quoteTransfer,
        string $configurableBundleGroupKey
    ): ItemCollectionTransfer {
        $itemCollection = new ItemCollectionTransfer();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getConfiguredBundle()->getGroupKey() === $configurableBundleGroupKey) {
                $itemCollection->addItem($itemTransfer);
            }
        }

        return $itemCollection;
    }
}
