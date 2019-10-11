<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteApprovalFacadeInterface
{
    /**
     * Specification:
     * - Returns unsuccessful response with corresponding message if target quote has no items.
     * - Share cart to approver with read only access.
     * - Removes all existing cart sharing.
     * - Merges `Quote` loaded from persistance with `QuoteApprovalRequest::quote` if it is provided.
     * - Locks quote.
     * - Creates new QuoteApproval request in status `waiting`.
     * - Returns quote approval response with updated quote.
     * - Requires QuoteApprovalRequestTransfer::quote field to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function createQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer;

    /**
     * Specification:
     * - Executes QuoteApprovalUnlockPreCheckPluginInterface plugins, unlocks quote if all registered plugins returns true.
     * - Removes all existing cart sharing.
     * - Removes quote approval.
     * - Returns quote approval response with updated quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function removeQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer;

    /**
     * Specification:
     * - Returns list of company users that can approve quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getQuoteApproverList(QuoteTransfer $quoteTransfer): CompanyUserCollectionTransfer;

    /**
     * Specification:
     * - Returns list of quote approval transfers by quote id.
     *
     * @api
     *
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer[]
     */
    public function getQuoteApprovalsByIdQuote(int $idQuote): array;

    /**
     * Specification:
     * - Checks that Approver can approve request.
     * - Checks that status is "Waiting".
     * - Sets quote approval request status "Approved" if checks are true.
     * - Returns quote approval response with updated quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function approveQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer;

    /**
     * Specification:
     * - Checks that Approver can approve request.
     * - Checks that status is "Waiting".
     * - Sets quote approval request status "Declined" if checks are true.
     * - Executes QuoteApprovalUnlockPreCheckPluginInterface plugins, unlocks quote if all registered plugins returns true.
     * - Returns quote approval response with updated quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function declineQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer;

    /**
     * Specification:
     * - Removes all approvals for quote from Persistence.
     *
     * @api
     *
     * @param int $idQuote
     *
     * @return void
     */
    public function removeApprovalsByIdQuote(int $idQuote): void;

    /**
     * Specification:
     * - Removes all approvals for quote from Persistence.
     * - Adjusts data related to quote approval in quote accordingly.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function sanitizeQuoteApproval(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Returns response with boolean isSuccess and an array of errors.
     * - Returns isSuccess false if customer does't have RequestQuoteApprovalPermissionPlugin permission assigned.
     * - Returns isSuccess false if executing of PlaceOrderPermissionPlugin permission returns true.
     * - Returns isSuccess false if quote approval status is not `approved`.
     * - Returns isSuccess true otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function isQuoteReadyForCheckout(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): CheckoutResponseTransfer;

    /**
     * Specification:
     * - Returns Quote fields allowed for saving if quote approval request is waiting or approved.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    public function getQuoteFieldsAllowedForSavingByQuoteApprovalStatus(QuoteTransfer $quoteTransfer): array;

    /**
     * Specification:
     * - Returns true if quote approval status is approved or waiting.
     * - Returns false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteInApprovalProcess(QuoteTransfer $quoteTransfer): bool;
}
