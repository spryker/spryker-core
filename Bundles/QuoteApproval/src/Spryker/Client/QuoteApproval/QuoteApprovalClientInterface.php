<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteApprovalClientInterface
{
    /**
     * Specification:
     * - Calculates approval status for quote.
     * - Returns status `Approved` if at least one approval request has status `Approved`.
     * - Returns status `Waiting` if at least one approval request in status `Waiting` and there is no `Approved` requests.
     * - Returns status `Declined` if all all approval requests are declined.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    public function calculateQuoteStatus(QuoteTransfer $quoteTransfer): ?string;

    /**
     * Specification:
     * - Returns unsuccessful response with corresponding message if quote id is not provided.
     * - Makes zed request.
     * - Returns unsuccessful response with corresponding message if target quote has no items.
     * - Share cart to approver with read only access.
     * - Removes all existing cart sharing.
     * - Locks quote.
     * - Creates new QuoteApproval request in status `waiting`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function createQuoteApproval(
        QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
    ): QuoteApprovalResponseTransfer;

    /**
     * Specification:
     * - Makes zed request.
     * - Executes QuoteApprovalUnlockPreCheckPluginInterface plugins, unlocks quote if all registered plugins returns true.
     * - Removes cart sharing with approver.
     * - Removes quote approval request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function removeQuoteApproval(
        QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
    ): QuoteApprovalResponseTransfer;

    /**
     * Specification:
     * - Makes zed request.
     * - Returns collection of company users that can approve quote.
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
     * - Returns false if customer does't have RequestQuoteApprovalPermissionPlugin permission assigned.
     * - Returns false if executing of PlaceOrderPermissionPlugin permission returns true.
     * - Returns false if quote approval status is `approved`.
     * - Returns true otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteApprovalRequired(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     * - Returns true if quote status is `waiting`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteWaitingForApproval(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     * - Returns true if quote status is `approved`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteApproved(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     * - Returns highest limit calculated from all ApproveQuotePermissionPlugin permissions assigned to company user.
     * - Returns null if no permissions found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return int|null
     */
    public function calculateApproveQuotePermissionLimit(QuoteTransfer $quoteTransfer, CompanyUserTransfer $companyUserTransfer): ?int;

    /**
     * Specification:
     * - Returns highest limit calculated from all PlaceOrderPermissionPlugin permissions assigned to company user.
     * - Returns null if no permissions found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return int|null
     */
    public function calculatePlaceOrderPermissionLimit(QuoteTransfer $quoteTransfer, CompanyUserTransfer $companyUserTransfer): ?int;

    /**
     * Specification:
     * - Sends Zed request to approve quote approval request.
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
     * - Returns quote approval which waiting for approve from specified company user.
     * - Returns null if approval not found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer|null
     */
    public function findWaitingQuoteApprovalByIdCompanyUser(QuoteTransfer $quoteTransfer, int $idCompanyUser): ?QuoteApprovalTransfer;

    /**
     * Specification:
     * - Returns true if customer has `ApproveQuotePermissionPlugin` and it's execution returns true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function canQuoteBeApprovedByCurrentCustomer(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     * - Returns true if at least 1 approval request assigned to specified company user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idCompanyUser
     *
     * @return bool
     */
    public function isCompanyUserInQuoteApproverList(QuoteTransfer $quoteTransfer, int $idCompanyUser): bool;
}
