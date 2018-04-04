<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartNote\QuoteStorageStrategy;

use Generated\Shared\Transfer\QuoteCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteItemCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\CartNote\Dependency\Client\CartNoteToQuoteClientInterface;
use Spryker\Client\CartNote\Zed\CartNoteStubInterface;
use Spryker\Shared\Quote\QuoteConfig;

class DatabaseQuoteStorageStrategy implements QuoteStorageStrategyInterface
{
    /**
     * @var \Spryker\Client\CartNote\Dependency\Client\CartNoteToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Client\CartNote\Zed\CartNoteStubInterface
     */
    protected $cartNoteZedStub;

    /**
     * @param \Spryker\Client\CartNote\Dependency\Client\CartNoteToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\CartNote\Zed\CartNoteStubInterface $cartNoteZedStub
     */
    public function __construct(CartNoteToQuoteClientInterface $quoteClient, CartNoteStubInterface $cartNoteZedStub)
    {
        $this->quoteClient = $quoteClient;
        $this->cartNoteZedStub = $cartNoteZedStub;
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
        $customerTransfer = $quoteTransfer->getCustomer();
        $quoteCartNoteRequestTransfer = new QuoteCartNoteRequestTransfer();
        $quoteCartNoteRequestTransfer->setCustomer($customerTransfer)
            ->setIdQuote($quoteTransfer->getIdQuote())
            ->setCartNote($note);

        $quoteResponseTransfer = $this->cartNoteZedStub->setNoteToQuote($quoteCartNoteRequestTransfer);
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
        }

        return $quoteResponseTransfer;
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
        $customerTransfer = $quoteTransfer->getCustomer();

        $quoteItemCartNoteRequestTransfer = new QuoteItemCartNoteRequestTransfer();
        $quoteItemCartNoteRequestTransfer->setCustomer($customerTransfer)
            ->setIdQuote($quoteTransfer->getIdQuote())
            ->setSku($sku)
            ->setGroupKey($groupKey)
            ->setCartNote($note);
        $quoteResponseTransfer = $this->cartNoteZedStub->setNoteToQuoteItem($quoteItemCartNoteRequestTransfer);
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
        }

        return $quoteResponseTransfer;
    }
}
