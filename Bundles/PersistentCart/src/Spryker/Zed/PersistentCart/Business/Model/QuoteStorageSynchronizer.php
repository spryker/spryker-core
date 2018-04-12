<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\QuoteMergeRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
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
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface
     */
    protected $quoteResponseExpander;

    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteMergerInterface
     */
    protected $quoteMerger;

    /**
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface $quoteResponseExpander
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteMergerInterface $quoteMerger
     */
    public function __construct(
        PersistentCartToCartFacadeInterface $cartFacade,
        PersistentCartToQuoteFacadeInterface $quoteFacade,
        QuoteResponseExpanderInterface $quoteResponseExpander,
        QuoteMergerInterface $quoteMerger
    ) {
        $this->cartFacade = $cartFacade;
        $this->quoteFacade = $quoteFacade;
        $this->quoteResponseExpander = $quoteResponseExpander;
        $this->quoteMerger = $quoteMerger;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteSyncRequestTransfer $quoteSyncRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function syncStorageQuote(QuoteSyncRequestTransfer $quoteSyncRequestTransfer): QuoteResponseTransfer
    {
        $this->assertDatabaseStorageStrategy();
        $this->validateRequest($quoteSyncRequestTransfer);

        $customerTransfer = $quoteSyncRequestTransfer->getCustomerTransfer();
        $quoteTransfer = $quoteSyncRequestTransfer->getQuoteTransfer();
        $customerQuoteTransfer = $this->quoteFacade->findQuoteByCustomer($customerTransfer);

        if ($customerQuoteTransfer->getIsSuccessful()) {
            $quoteTransfer = $this->mergeQuotes($quoteTransfer, $customerQuoteTransfer->getQuoteTransfer());
        }

        $quoteTransfer->setCustomer($customerTransfer);
        if (count($quoteTransfer->getItems())) {
            $quoteTransfer = $this->cartFacade->reloadItems($quoteTransfer);
        }

        return $this->quoteResponseExpander->expand($this->saveQuote($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteSyncRequestTransfer $quoteSyncRequestTransfer
     *
     * @return void
     */
    protected function validateRequest(QuoteSyncRequestTransfer $quoteSyncRequestTransfer): void
    {
        $quoteSyncRequestTransfer
            ->requireCustomerTransfer()
            ->requireQuoteTransfer();

        $quoteSyncRequestTransfer->getCustomerTransfer()->requireCustomerReference();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $targetQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mergeQuotes(QuoteTransfer $targetQuoteTransfer, QuoteTransfer $sourceQuoteTransfer): QuoteTransfer
    {
        if (!count($targetQuoteTransfer->getItems())) {
            $sourceQuoteTransfer->fromArray($targetQuoteTransfer->modifiedToArray(), true);
            return $sourceQuoteTransfer;
        }
        $quoteMergeRequestTransfer = new QuoteMergeRequestTransfer();
        $quoteMergeRequestTransfer
            ->setTargetQuote($targetQuoteTransfer)
            ->setSourceQuote($sourceQuoteTransfer);

        $targetQuoteTransfer = $this->quoteMerger->merge($quoteMergeRequestTransfer);
        $targetQuoteTransfer->setIdQuote($sourceQuoteTransfer->getIdQuote());

        return $targetQuoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function saveQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        if ($quoteTransfer->getIdQuote()) {
            return $this->quoteFacade->updateQuote($quoteTransfer);
        }

        return $this->quoteFacade->createQuote($quoteTransfer);
    }

    /**
     * @throws \Spryker\Zed\PersistentCart\Business\Exception\QuoteSynchronizationNotAvailable
     *
     * @return void
     */
    protected function assertDatabaseStorageStrategy(): void
    {
        if ($this->quoteFacade->getStorageStrategy() !== SharedQuoteConfig::STORAGE_STRATEGY_DATABASE) {
            throw new QuoteSynchronizationNotAvailable(
                sprintf(
                    'Synchronization available for "%s" storage strategy only',
                    SharedQuoteConfig::STORAGE_STRATEGY_DATABASE
                )
            );
        }
    }
}
