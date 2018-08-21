<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use Spryker\Zed\CartsRestApi\Persistence\CartsRestApiEntityManagerInterface;
use Spryker\Zed\CartsRestApi\Persistence\CartsRestApiRepositoryInterface;

class QuoteUuidWriter implements QuoteUuidWriterInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Persistence\CartsRestApiEntityManagerInterface
     */
    protected $quoteEntityManager;

    /**
     * @var \Spryker\Zed\CartsRestApi\Persistence\CartsRestApiRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @param \Spryker\Zed\CartsRestApi\Persistence\CartsRestApiEntityManagerInterface $quoteEntityManager
     * @param \Spryker\Zed\CartsRestApi\Persistence\CartsRestApiRepositoryInterface $quoteRepository
     */
    public function __construct(
        CartsRestApiEntityManagerInterface $quoteEntityManager,
        CartsRestApiRepositoryInterface $quoteRepository
    ) {
        $this->quoteEntityManager = $quoteEntityManager;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @return void
     */
    public function updateQuotesUuid(): void
    {
        do {
            $quotes = $this->quoteRepository->getQuotesWithoutUuid();

            foreach ($quotes as $quote) {
                $this->quoteEntityManager->saveQuoteWithoutUuid($quote);
            }
        } while ($quotes);
    }
}
