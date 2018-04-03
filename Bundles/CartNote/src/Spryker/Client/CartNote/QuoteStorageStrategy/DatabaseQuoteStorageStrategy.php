<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartNote\QuoteStorageStrategy;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Client\CartNote\Dependency\Client\CartNoteToPersistentCartClientInterface;
use Spryker\Client\CartNote\Dependency\Client\CartNoteToQuoteClientInterface;
use Spryker\Shared\Quote\QuoteConfig;

class DatabaseQuoteStorageStrategy implements QuoteStorageStrategyInterface
{
    /**
     * @var \Spryker\Client\CartNote\Dependency\Client\CartNoteToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Client\CartNote\Dependency\Client\CartNoteToPersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @param \Spryker\Client\CartNote\Dependency\Client\CartNoteToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\CartNote\Dependency\Client\CartNoteToPersistentCartClientInterface $persistentCartClient
     */
    public function __construct(CartNoteToQuoteClientInterface $quoteClient, CartNoteToPersistentCartClientInterface $persistentCartClient)
    {
        $this->quoteClient = $quoteClient;
        $this->persistentCartClient = $persistentCartClient;
    }

    /**
     * @return string
     */
    public function getStorageStrategy(): string
    {
        return QuoteConfig::STORAGE_STRATEGY_DATABASE;
    }

    /**
     * @param string $note
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setNoteToQuote(string $note): QuoteResponseTransfer
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        $quoteUpdateRequestTransfer = $this->createQuoteUpdateRequest($quoteTransfer);
        $quoteUpdateRequestTransfer->getQuoteUpdateRequestAttributes()->setCartNote($note);

        return $this->persistentCartClient->updateQuote($quoteUpdateRequestTransfer);
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
        $quoteItemTransfer = $this->findQuoteItem($quoteTransfer, $sku, $groupKey);
        if (!$quoteItemTransfer) {
            $quoteResponseTransfer = new QuoteResponseTransfer();
            $quoteResponseTransfer->setIsSuccessful(false);

            return $quoteResponseTransfer;
        }
        $quoteItemTransfer->setCartNote($note);
        $quoteUpdateRequestTransfer = $this->createQuoteUpdateRequest($quoteTransfer);
        $quoteUpdateRequestTransfer->getQuoteUpdateRequestAttributes()->setItems($quoteTransfer->getItems());

        return $this->persistentCartClient->updateQuote($quoteUpdateRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findQuoteItem(QuoteTransfer $quoteTransfer, $sku, $groupKey = null): ?ItemTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (($itemTransfer->getSku() === $sku && $groupKey === null) ||
                $itemTransfer->getGroupKey() === $groupKey) {
                return $itemTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteUpdateRequestTransfer
     */
    protected function createQuoteUpdateRequest(QuoteTransfer $quoteTransfer): QuoteUpdateRequestTransfer
    {
        $quoteUpdateRequestTransfer = new QuoteUpdateRequestTransfer();
        $quoteUpdateRequestTransfer->setIdQuote($quoteTransfer->getIdQuote());
        $quoteUpdateRequestTransfer->setCustomer($quoteTransfer->getCustomer());
        $quoteUpdateRequestAttributesTransfer = new QuoteUpdateRequestAttributesTransfer();
        $quoteUpdateRequestTransfer->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);

        return $quoteUpdateRequestTransfer;
    }
}
