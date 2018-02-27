<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\Session;

use Generated\Shared\Transfer\QuoteMergeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface;
use Spryker\Client\Quote\Zed\QuoteStubInterface;

class QuoteResolver implements QuoteResolverInterface
{
    /**
     * @var \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface
     */
    protected $sessionStorageStrategy;

    /**
     * @var \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface
     */
    protected $currentStorageStrategy;

    /**
     * @var \Spryker\Client\Quote\Zed\QuoteStub
     */
    protected $zedQuoteStub;

    /**
     * @param \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface $sessionStorageStrategy
     * @param \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface $currentStorageStrategy
     * @param \Spryker\Client\Quote\Zed\QuoteStubInterface $zedQuoteStub
     */
    public function __construct(
        StorageStrategyInterface $sessionStorageStrategy,
        StorageStrategyInterface $currentStorageStrategy,
        QuoteStubInterface $zedQuoteStub
    ) {
        $this->sessionStorageStrategy = $sessionStorageStrategy;
        $this->currentStorageStrategy = $currentStorageStrategy;
        $this->zedQuoteStub = $zedQuoteStub;
    }

    /**
     * @return void
     */
    public function resolve()
    {
        if ($this->sessionStorageStrategy->getStorageType() === $this->currentStorageStrategy->getStorageType()) {
            return;
        }

        $sessionStorageQuote = $this->sessionStorageStrategy->getQuote();
        if ($sessionStorageQuote->getCustomer()) {
            return;
        }

        $currentStorageQuote = $this->currentStorageStrategy->getQuote();
        $this->quoteMerge($sessionStorageQuote, $currentStorageQuote);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $sessionStorageQuote
     * @param \Generated\Shared\Transfer\QuoteTransfer $currentStorageQuote
     *
     * @return void
     */
    protected function quoteMerge(QuoteTransfer $sessionStorageQuote, QuoteTransfer $currentStorageQuote)
    {
        $quoteMergeTransfer = new QuoteMergeTransfer();
        $quoteMergeTransfer->setTargetQuote($currentStorageQuote);
        $quoteMergeTransfer->setSourceQuote($sessionStorageQuote);
        $quoteTransfer = $this->zedQuoteStub->mergeQuotes($quoteMergeTransfer)->getQuoteTransfer();
        $this->sessionStorageStrategy->saveQuote($quoteTransfer);
    }
}
