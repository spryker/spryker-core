<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use DateTime;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface;

class QuoteRequestCleaner implements QuoteRequestCleanerInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface
     */
    protected $quoteRequestEntityManager;

    /**
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface $quoteRequestEntityManager
     */
    public function __construct(QuoteRequestEntityManagerInterface $quoteRequestEntityManager)
    {
        $this->quoteRequestEntityManager = $quoteRequestEntityManager;
    }

    /**
     * @return void
     */
    public function closeOutdatedQuoteRequests(): void
    {
        $this->quoteRequestEntityManager->closeOutdatedQuoteRequests(new DateTime());
    }
}
