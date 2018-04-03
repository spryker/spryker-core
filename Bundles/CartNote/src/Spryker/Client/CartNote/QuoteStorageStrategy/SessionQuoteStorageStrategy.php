<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartNote\QuoteStorageStrategy;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\CartNote\Dependency\Client\CartNoteToQuoteClientInterface;
use Spryker\Shared\Quote\QuoteConfig;

class SessionQuoteStorageStrategy implements QuoteStorageStrategyInterface
{
    /**
     * @var \Spryker\Client\CartNote\Dependency\Client\CartNoteToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\CartNote\Dependency\Client\CartNoteToQuoteClientInterface $quoteClient
     */
    public function __construct(CartNoteToQuoteClientInterface $quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

    /**
     * @return string
     */
    public function getStorageStrategy(): string
    {
        return QuoteConfig::STORAGE_STRATEGY_SESSION;
    }

    /**
     * @param string $note
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setNoteToQuote(string $note): QuoteResponseTransfer
    {
        $quoteTransfer = $this->quoteClient->getQuote();
        $quoteTransfer->setCartNote($note);
        $this->quoteClient->setQuote($quoteTransfer);

        $quoteNoteResponseTransfer = new QuoteResponseTransfer();
        $quoteNoteResponseTransfer->setIsSuccessful(true);
        return $quoteNoteResponseTransfer;
    }

    /**
     * @param string $note
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setNoteToQuoteItem(string $note, string $sku, string $groupKey = null): QuoteResponseTransfer
    {
        $quoteTransfer = $this->quoteClient->getQuote();
        $quoteItemTransfer = $this->findItem($quoteTransfer, $sku, $groupKey);

        $quoteNoteResponseTransfer = new QuoteResponseTransfer();
        $quoteNoteResponseTransfer->setIsSuccessful(false);
        if ($quoteItemTransfer) {
            $quoteItemTransfer->setCartNote($note);
            $this->quoteClient->setQuote($quoteTransfer);
            $quoteNoteResponseTransfer->setIsSuccessful(true);
        }

        return $quoteNoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItem($quoteTransfer, $sku, $groupKey = null): ?ItemTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (($itemTransfer->getSku() === $sku && $groupKey === null) ||
                $itemTransfer->getGroupKey() === $groupKey) {
                return $itemTransfer;
            }
        }

        return null;
    }
}
