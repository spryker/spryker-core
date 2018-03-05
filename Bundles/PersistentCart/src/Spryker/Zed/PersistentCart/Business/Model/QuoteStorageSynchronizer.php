<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\QuoteMergeRequestTransfer;
use Generated\Shared\Transfer\QuoteSyncRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Quote\QuoteConfig as SharedQuoteConfig;
use Spryker\Zed\PersistentCart\Business\Exception\QuoteSynchronizationNotAvailable;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface;

class QuoteStorageSynchronizer implements QuoteStorageSynchronizerInterface
{
    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(
        PersistentCartToCartFacadeInterface $cartFacade,
        PersistentCartToQuoteFacadeInterface $quoteFacade
    ) {
        $this->cartFacade = $cartFacade;
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteSyncRequestTransfer $quoteSyncRequestTransfer
     *
     * @throws \Spryker\Zed\PersistentCart\Business\Exception\QuoteSynchronizationNotAvailable
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function syncStorageQuote(QuoteSyncRequestTransfer $quoteSyncRequestTransfer): QuoteTransfer
    {
        if ($this->quoteFacade->getStorageStrategy() !== SharedQuoteConfig::STORAGE_STRATEGY_DATABASE) {
            throw new QuoteSynchronizationNotAvailable(
                sprintf('Synchronization available for "%s" storage strategy only', SharedQuoteConfig::STORAGE_STRATEGY_DATABASE)
            );
        }

        $this->validateRequest($quoteSyncRequestTransfer);
        $customerTransfer = $quoteSyncRequestTransfer->getCustomerTransfer();
        $quoteTransfer = $quoteSyncRequestTransfer->getQuoteTransfer();
        $customerQuoteTransfer = $this->quoteFacade->findQuoteByCustomer($customerTransfer);

        if ($customerQuoteTransfer->getIsSuccessful()) {
            $quoteTransfer = $this->mergeQuotes($quoteTransfer, $customerQuoteTransfer->getQuoteTransfer());
        }
        $quoteTransfer->setCustomer($customerTransfer);
        $quoteTransfer = $this->cartFacade->reloadItems($quoteTransfer);
        $this->quoteFacade->persistQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteSyncRequestTransfer $quoteSyncRequestTransfer
     *
     * @return void
     */
    protected function validateRequest(QuoteSyncRequestTransfer $quoteSyncRequestTransfer)
    {
        $quoteSyncRequestTransfer->requireCustomerTransfer();
        $quoteSyncRequestTransfer->requireQuoteTransfer();
        $quoteSyncRequestTransfer->getCustomerTransfer()->requireCustomerReference();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $targetQuote
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mergeQuotes(QuoteTransfer $targetQuote, QuoteTransfer $sourceQuote)
    {
        $quoteMergeRequestTransfer = new QuoteMergeRequestTransfer();
        $quoteMergeRequestTransfer->setTargetQuote($targetQuote);
        $quoteMergeRequestTransfer->setSourceQuote($sourceQuote);
        $targetQuote = $this->quoteFacade->mergeQuotes($quoteMergeRequestTransfer);
        $targetQuote->setIdQuote($sourceQuote->getIdQuote());

        return $targetQuote;
    }
}
