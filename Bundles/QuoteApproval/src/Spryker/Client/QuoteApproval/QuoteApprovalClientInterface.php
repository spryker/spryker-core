<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\QuoteApprovalCancelRequestTransfer;
use Generated\Shared\Transfer\QuoteApproveRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteApprovalClientInterface
{
    /**
     * Specification:
     * - Calculates approval status for quote
     * - Returns status `Approved` if at least one approval request has status `Approved`.
     * - Returns status `Waiting` if at least one approval request in status `Waiting` and all other are `Desclined`.
     * - Returns status `Desclined` if all all approval requests are declined.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    public function getQuoteStatus(QuoteTransfer $quoteTransfer): ?string;

    /**
     * Specification:
     * - Share quote to approver with read only access.
     * - Locks quote.
     * - Create QuoteApproval with `Waiting` status.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApproveRequestTransfer $quoteApproveRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function sendApproveRequest(QuoteApproveRequestTransfer $quoteApproveRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Unlocks quote.
     * - Removes cart sharing with approver.
     * - Removes quote approval request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalCancelRequestTransfer $quoteApprovalCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function cancelApprovalRequest(
        QuoteApprovalCancelRequestTransfer $quoteApprovalCancelRequestTransfer
    ): QuoteResponseTransfer;

    /**
     * Specification:
     * - Returns collection of CompanyUser's which can approve particular quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getPotentialQuoteApproversList(QuoteTransfer $quoteTransfer): CompanyUserCollectionTransfer;
}
