<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use DateTime;
use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface;

class QuoteRequestCleaner implements QuoteRequestCleanerInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface
     */
    protected $quoteRequestEntityManager;

    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface
     */
    protected $quoteRequestRepository;

    /**
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface $quoteRequestEntityManager
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface $quoteRequestRepository
     */
    public function __construct(
        QuoteRequestEntityManagerInterface $quoteRequestEntityManager,
        QuoteRequestRepositoryInterface $quoteRequestRepository
    ) {
        $this->quoteRequestEntityManager = $quoteRequestEntityManager;
        $this->quoteRequestRepository = $quoteRequestRepository;
    }

    /**
     * @return void
     */
    public function closeOutdatedQuoteRequests(): void
    {
        $this->quoteRequestEntityManager->closeOutdatedQuoteRequests(new DateTime());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return void
     */
    public function closeQuoteRequest(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): void
    {
        $quoteRequestCriteriaTransfer->requireQuoteRequestReference();

        $quoteRequestTransfer = $this->findQuoteRequestTransfer($quoteRequestCriteriaTransfer);

        if (!$quoteRequestTransfer) {
            return;
        }

        $quoteRequestTransfer->setStatus(SharedQuoteRequestConfig::STATUS_CLOSED);
        $this->quoteRequestEntityManager->updateQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    protected function findQuoteRequestTransfer(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): ?QuoteRequestTransfer
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestCriteriaTransfer->getQuoteRequestReference());

        $quoteRequestTransfers = $this->quoteRequestRepository
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getArrayCopy();

        return array_shift($quoteRequestTransfers);
    }
}
