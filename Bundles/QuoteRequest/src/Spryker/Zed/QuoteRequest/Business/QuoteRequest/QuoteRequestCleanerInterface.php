<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;

interface QuoteRequestCleanerInterface
{
    /**
     * @return void
     */
    public function closeOutdatedQuoteRequests(): void;

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return void
     */
    public function closeQuoteRequest(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): void;
}
