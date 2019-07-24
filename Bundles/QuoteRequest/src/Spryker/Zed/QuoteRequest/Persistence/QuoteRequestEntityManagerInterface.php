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
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function createQuoteRequestVersion(QuoteRequestVersionTransfer $quoteRequestVersionTransfer): QuoteRequestVersionTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function updateQuoteRequestVersion(QuoteRequestVersionTransfer $quoteRequestVersionTransfer): QuoteRequestVersionTransfer;

    /**
     * @param \DateTime $validUntil
     *
     * @return void
     */
    public function closeOutdatedQuoteRequests(DateTime $validUntil): void;

    /**
     * @param string $quoteRequestReference
     * @param string $fromStatus
     * @param string $toStatus
     *
     * @return bool
     */
    public function updateQuoteRequestStatus(string $quoteRequestReference, string $fromStatus, string $toStatus): bool;

    /**
     * @param int[] $quoteRequestIds
     *
     * @return void
     */
    public function deleteQuoteRequestsByIds(array $quoteRequestIds): void;

    /**
     * @param int[] $quoteRequestIds
     *
     * @return void
     */
    public function deleteQuoteRequestVersionsByQuoteRequestIds(array $quoteRequestIds): void;
}
