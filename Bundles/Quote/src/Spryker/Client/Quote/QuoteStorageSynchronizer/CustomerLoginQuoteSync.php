<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\QuoteStorageSynchronizer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteSyncRequestTransfer;
use Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface;
use Spryker\Client\Quote\Zed\QuoteStubInterface;
use Spryker\Shared\Quote\QuoteConfig;

class CustomerLoginQuoteSync implements CustomerLoginQuoteSyncInterface
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
     * @param \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface $currentStorageStrategy
     * @param \Spryker\Client\Quote\Zed\QuoteStubInterface $zedQuoteStub
     */
    public function __construct(
        StorageStrategyInterface $currentStorageStrategy,
        QuoteStubInterface $zedQuoteStub
    ) {
        $this->currentStorageStrategy = $currentStorageStrategy;
        $this->zedQuoteStub = $zedQuoteStub;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function syncQuoteForCustomer(CustomerTransfer $customerTransfer)
    {
        if ($this->currentStorageStrategy->getStorageStrategy() !== QuoteConfig::STORAGE_STRATEGY_DATABASE) {
            return;
        }

        $quoteTransfer = $this->currentStorageStrategy->getQuote();
        if ($quoteTransfer->getCustomer()) {
            return;
        }

        $quoteSyncRequestTransfer = new QuoteSyncRequestTransfer();
        $quoteSyncRequestTransfer->setQuoteTransfer($quoteTransfer);
        $quoteSyncRequestTransfer->setCustomerTransfer($customerTransfer);
        $quoteResponseTransfer = $this->zedQuoteStub->syncStorageQuote($quoteSyncRequestTransfer);
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->currentStorageStrategy->setQuote($quoteResponseTransfer->getQuoteTransfer());
        }
    }
}
