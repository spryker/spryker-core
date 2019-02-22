<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\QuoteRequest;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Client\QuoteRequest\QuoteRequestConfig;

class QuoteRequestChecker implements QuoteRequestCheckerInterface
{
    /**
     * @var \Spryker\Client\QuoteRequest\QuoteRequestConfig
     */
    protected $quoteRequestConfig;

    /**
     * @param \Spryker\Client\QuoteRequest\QuoteRequestConfig $quoteRequestConfig
     */
    public function __construct(QuoteRequestConfig $quoteRequestConfig)
    {
        $this->quoteRequestConfig = $quoteRequestConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteRequestCancelable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return in_array($quoteRequestTransfer->getStatus(), $this->quoteRequestConfig->getCancelableStatuses());
    }
}
