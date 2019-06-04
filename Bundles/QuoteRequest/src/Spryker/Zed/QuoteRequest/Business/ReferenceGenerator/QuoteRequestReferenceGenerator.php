<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\ReferenceGenerator;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface;
use Spryker\Zed\QuoteRequest\QuoteRequestConfig;

class QuoteRequestReferenceGenerator implements QuoteRequestReferenceGeneratorInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequest\QuoteRequestConfig
     */
    protected $quoteRequestConfig;

    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface
     */
    protected $quoteRequestRepository;

    /**
     * @param \Spryker\Zed\QuoteRequest\QuoteRequestConfig $quoteRequestConfig
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface $quoteRequestRepository
     */
    public function __construct(
        QuoteRequestConfig $quoteRequestConfig,
        QuoteRequestRepositoryInterface $quoteRequestRepository
    ) {
        $this->quoteRequestConfig = $quoteRequestConfig;
        $this->quoteRequestRepository = $quoteRequestRepository;
    }

    /**
     * @param string $customerReference
     *
     * @return string
     */
    public function generateQuoteRequestReference(string $customerReference)
    {
        $customerQuoteRequestCounter = $this->quoteRequestRepository->countCustomerQuoteRequests($customerReference);

        return sprintf(
            $this->quoteRequestConfig->getQuoteRequestReferenceFormat(),
            $customerReference,
            $customerQuoteRequestCounter + 1
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return string
     */
    public function generateQuoteRequestVersionReference(
        QuoteRequestTransfer $quoteRequestTransfer,
        QuoteRequestVersionTransfer $quoteRequestVersionTransfer
    ): string {
        return sprintf(
            $this->quoteRequestConfig->getQuoteRequestReferenceFormat(),
            $quoteRequestTransfer->getQuoteRequestReference(),
            $quoteRequestVersionTransfer->getVersion()
        );
    }
}
