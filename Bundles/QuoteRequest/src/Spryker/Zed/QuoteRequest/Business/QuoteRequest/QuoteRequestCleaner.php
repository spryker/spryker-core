<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface;

class QuoteRequestCleaner implements QuoteRequestCleanerInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface
     */
    protected $quoteRequestRepository;

    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface
     */
    protected $quoteRequestEntityManager;

    /**
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface $quoteRequestRepository
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface $quoteRequestEntityManager
     */
    public function __construct(
        QuoteRequestRepositoryInterface $quoteRequestRepository,
        QuoteRequestEntityManagerInterface $quoteRequestEntityManager
    ) {
        $this->quoteRequestRepository = $quoteRequestRepository;
        $this->quoteRequestEntityManager = $quoteRequestEntityManager;
    }

    /**
     * @return void
     */
    public function closeOutdatedQuoteRequests(): void
    {
        $outdatedQuoteRequestIds = $this->quoteRequestRepository->getOutdatedQuoteRequestIds();

        if (!$outdatedQuoteRequestIds) {
            return;
        }

        $this->quoteRequestEntityManager->closeQuoteRequests($outdatedQuoteRequestIds);
    }
}
