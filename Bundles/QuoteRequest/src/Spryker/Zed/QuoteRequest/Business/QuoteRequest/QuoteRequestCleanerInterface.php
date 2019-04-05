<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteRequestCleanerInterface
{
    /**
     * @return void
     */
    public function closeOutdatedQuoteRequests(): void;

    /**
     * @param string $quoteRequestVersionReference
     *
     * @return void
     */
    public function closeQuoteRequest(string $quoteRequestVersionReference): void;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function clearQuoteRequestFromQuote(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
