<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Model;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteSyncRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Quote\QuoteConfig as SharedQuoteConfig;
use Spryker\Zed\Quote\Business\Exception\QuoteSynchronizationNotAvailable;
use Spryker\Zed\Quote\Business\Model\QuoteWriterInterface;
use Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface;
use Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface;
use Spryker\Zed\Quote\QuoteConfig;

class QuoteStorageSynchronizer implements QuoteStorageSynchronizerInterface
{
    /**
     * @var \Spryker\Zed\Quote\Business\Model\QuoteMergerInterface
     */
    protected $quoteMerger;

    /**
     * @var \Spryker\Zed\Quote\QuoteConfig
     */
    protected $quoteConfig;

    /**
     * @var \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var \Spryker\Zed\Quote\Business\Model\QuoteWriterInterface
     */
    protected $quoteWriter;

    /**
     * @param \Spryker\Zed\Quote\QuoteConfig $quoteConfig
     * @param \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface $quoteRepository
     * @param \Spryker\Zed\Quote\Business\Model\QuoteWriterInterface $quoteWriter
     * @param \Spryker\Zed\Quote\Business\Model\QuoteMergerInterface $quoteMerger
     */
    public function __construct(
        QuoteConfig $quoteConfig,
        QuoteRepositoryInterface $quoteRepository,
        QuoteWriterInterface $quoteWriter,
        QuoteMergerInterface $quoteMerger
    ) {
        $this->quoteConfig = $quoteConfig;
        $this->quoteMerger = $quoteMerger;
        $this->quoteRepository = $quoteRepository;
        $this->quoteWriter = $quoteWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteSyncRequestTransfer $quoteSyncRequestTransfer
     *
     * @throws \Spryker\Zed\Quote\Business\Exception\QuoteSynchronizationNotAvailable
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function syncStorageQuote(QuoteSyncRequestTransfer $quoteSyncRequestTransfer): QuoteResponseTransfer
    {
        if ($this->quoteConfig->getStorageStrategy() !== SharedQuoteConfig::STORAGE_STRATEGY_DATABASE) {
            throw new QuoteSynchronizationNotAvailable(
                sprintf('Synchronization available for "%s" storage strategy only', SharedQuoteConfig::STORAGE_STRATEGY_DATABASE)
            );
        }

        $this->validateRequest($quoteSyncRequestTransfer);
        $customerTransfer = $quoteSyncRequestTransfer->getCustomerTransfer();
        $quoteTransfer = $quoteSyncRequestTransfer->getQuoteTransfer();
        $customerQuoteTransfer = $this->quoteRepository->findQuoteByCustomer($customerTransfer->getCustomerReference());

        if ($customerQuoteTransfer) {
            $quoteTransfer = $this->mergeQuotes($quoteTransfer, $customerQuoteTransfer);
        }

        $quoteTransfer->setCustomer($customerTransfer);
        return $this->quoteWriter->save($quoteTransfer);
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
        $targetQuote = $this->quoteMerger->merge($targetQuote, $sourceQuote);
        $targetQuote->setIdQuote($sourceQuote->getIdQuote());

        return $targetQuote;
    }
}
