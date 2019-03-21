<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence;

use DateTime;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;

interface QuoteRequestEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function createQuoteRequestVersion(QuoteRequestVersionTransfer $quoteRequestTransfer): QuoteRequestVersionTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function updateQuoteRequestVersion(QuoteRequestVersionTransfer $quoteRequestTransfer): QuoteRequestVersionTransfer;

    /**
     * @param \DateTime $validUntil
     *
     * @return void
     */
    public function closeOutdatedQuoteRequests(DateTime $validUntil): void;
}
