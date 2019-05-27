<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CommentCollectionTransfer;
use Generated\Shared\Transfer\CommentFilterTransfer;
use Generated\Shared\Transfer\CommentResponseTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CommentVersionCollectionTransfer;
use Generated\Shared\Transfer\CommentVersionFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CommentFacadeInterface
{
    /**
     * Specification:
     * - Creates "Request for Quote" for the provided company user with "draft" status.
     * - Generates unique reference number.
     * - Generates version for the "Request for Quote" entity.
     * - Generates version reference based on unique reference number and version number.
     * - Maps Quote to CalculableObject and runs all calculator plugins before saving.
     * - Stores provided metadata.
     * - Stores provided quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function createComment(CommentTransfer $quoteRequestTransfer): CommentResponseTransfer;

    /**
     * Specification:
     * - Finds a "Request for Quote" by CommentTransfer::idComment in the transfer.
     * - Expects "Request for Quote" status to be "draft".
     * - Updates metadata in latest version.
     * - Updates quote in latest version.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function updateComment(CommentTransfer $quoteRequestTransfer): CommentResponseTransfer;

    /**
     * Specification:
     * - Looks up one "Request for Quote" by provided quote request reference.
     * - Expects the related company user to be provided.
     * - Expects "Request for Quote" status to be "ready".
     * - Creates latest version from previous version.
     * - Sets status to "draft".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function reviseComment(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer;

    /**
     * Specification:
     * - Looks up one "Request for Quote" by provided quote request reference.
     * - Expects the related company user to be provided.
     * - Expects "Request for Quote" status to be "draft", "waiting", "ready".
     * - Sets status to "cancelled".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function cancelComment(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer;

    /**
     * Specification:
     * - Looks up one "Request for Quote" by provided quote request version reference.
     * - Sets status to "Closed".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function closeComment(QuoteTransfer $quoteTransfer): void;

    /**
     * Specification:
     * - Expects quote request reference to be provided.
     * - Retrieves "Request for Quote" entity filtered by reference.
     * - Expects "Request for Quote" status to be "draft".
     * - Changes status to "waiting".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function sendCommentToUser(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer;

    /**
     * Specification:
     * - Creates "Request for Quote" for the provided company user with "in-progress" status.
     * - Generates unique reference number.
     * - Generates version for the "Request for Quote" entity.
     * - Generates version reference based on unique reference number and version number.
     * - Sets field is_latest_version_visible to true.
     * - Stores empty metadata.
     * - Stores empty quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function createCommentForCompanyUser(CommentTransfer $quoteRequestTransfer): CommentResponseTransfer;

    /**
     * Specification:
     * - Finds a "Request for Quote" by CommentTransfer::idComment in the transfer.
     * - Expects "Request for Quote" status to be "draft", "in-progress".
     * - Updates valid_until, is_latest_version_visible fields in RfQ entity.
     * - Updates metadata in latest version.
     * - Updates quote in latest version.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function updateCommentForCompanyUser(CommentTransfer $quoteRequestTransfer): CommentResponseTransfer;

    /**
     * Specification:
     * - Looks up one "Request for Quote" by provided quote request reference.
     * - Expects "Request for Quote" status to be "waiting", "ready", "draft".
     * - Creates latest version from previous version.
     * - Sets field is_latest_version_visible to true.
     * - Sets status to "in-progress".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function reviseCommentForCompanyUser(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer;

    /**
     * Specification:
     * - Looks up one "Request for Quote" by provided quote request reference.
     * - Expects "Request for Quote" status to not be "canceled", "closed".
     * - Sets status to "cancelled".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function cancelCommentForCompanyUser(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer;

    /**
     * Specification:
     * - Expects quote request reference to be provided.
     * - Retrieves "Request for Quote" entity filtered by reference.
     * - Expects "Request for Quote" status to be "draft", "in-progress".
     * - Updates field is_latest_version_visible to true.
     * - Changes status to "ready".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function sendCommentToCompanyUser(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer;

    /**
     * Specification:
     * - Retrieves "Request for Quote" entities filtered by company user.
     * - Filters by quote request reference when provided.
     * - Selects latest visible quote request version.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentCollectionTransfer
     */
    public function getCommentCollectionByFilter(CommentFilterTransfer $quoteRequestFilterTransfer): CommentCollectionTransfer;

    /**
     * Specification:
     * - Retrieves "Request for Quote" versions.
     * - Filters by "Request for Quote" id when provided.
     * - Filters by quote request version reference when provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentVersionFilterTransfer $quoteRequestVersionFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentVersionCollectionTransfer
     */
    public function getCommentVersionCollectionByFilter(CommentVersionFilterTransfer $quoteRequestVersionFilterTransfer): CommentVersionCollectionTransfer;

    /**
     * Specification:
     * - Validates quote request if quote request reference exists in quote.
     * - Checks if quote request version exists in database.
     * - Checks status from quote request.
     * - Checks that the current version is the latest.
     * - Checks valid until from quote request with current time.
     * - Returns true if quote requests pass all checks.
     * - Adds error message if not valid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isCommentVersionReadyForCheckout(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool;

    /**
     * Specification:
     * - Retrieves requests for quote where valid_until less than current time and status is "ready".
     * - Updates requests of quote status to "closed".
     *
     * @api
     *
     * @return void
     */
    public function closeOutdatedComments(): void;

    /**
     * Specification:
     * - Sanitizes data related to request for quote in quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function sanitizeComment(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Retrieves "Request for Quote" entity.
     * - Expects the quote request reference to be provided.
     * - Filters by quote request company user id when provided.
     * - Selects latest visible quote request version.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function getComment(CommentFilterTransfer $quoteRequestFilterTransfer): CommentResponseTransfer;
}
