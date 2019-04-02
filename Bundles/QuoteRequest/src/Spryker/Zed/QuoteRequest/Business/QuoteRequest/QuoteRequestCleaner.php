<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use DateTime;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
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
     * @param string $quoteRequestVersionReference
     *
     * @return void
     */
    public function closeQuoteRequest(string $quoteRequestVersionReference): void
    {
        $quoteRequestTransfer = $this->findQuoteRequestTransferByVersionReference($quoteRequestVersionReference);

        if (!$quoteRequestTransfer) {
            return;
        }

        $quoteRequestTransfer->setStatus(SharedQuoteRequestConfig::STATUS_CLOSED);
        $this->quoteRequestEntityManager->updateQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @param string $quoteRequestVersionReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    protected function findQuoteRequestTransferByVersionReference(string $quoteRequestVersionReference): ?QuoteRequestTransfer
    {
        $quoteRequestVersionFilterTransfer = (new QuoteRequestVersionFilterTransfer())
            ->setQuoteRequestVersionReference($quoteRequestVersionReference);

        $quoteRequestVersionTransfers = $this->quoteRequestRepository
            ->getQuoteRequestVersionCollectionByFilter($quoteRequestVersionFilterTransfer)
            ->getQuoteRequestVersions()
            ->getArrayCopy();

        $quoteRequestVersionTransfer = array_shift($quoteRequestVersionTransfers);

        if (!$quoteRequestVersionTransfer) {
            return null;
        }

        return $quoteRequestVersionTransfer->getQuoteRequest();
    }
}
