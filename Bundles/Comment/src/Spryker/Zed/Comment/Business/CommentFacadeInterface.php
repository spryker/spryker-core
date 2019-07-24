<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business;

use Generated\Shared\Transfer\CommentFilterTransfer;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTagRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;

interface CommentFacadeInterface
{
    /**
     * Specification:
     * - Retrieves a comment thread using the provided owner type and owner ID if found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThreadByOwner(CommentRequestTransfer $commentRequestTransfer): ?CommentThreadTransfer;

    /**
     * Specification:
     * - Expects owner ID to be provided.
     * - Expects owner type to be provided.
     * - Expects comment message to be provided.
     * - Expects customer id to be provided.
     * - Creates comment thread if it does not exist in Persistence yet.
     * - Persists provided comment for the comment thread.
     * - Returns 'isSuccessful=true' with the up to date comment thread.
     * - Returns 'isSuccessful=false' with error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function addComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer;

    /**
     * Specification:
     * - Expects comment message to be provided.
     * - Expects customer id to be provided.
     * - Updates the provided comment by comment UUID in Persistence.
     * - Returns 'isSuccessful=true' with the up to date comment thread.
     * - Returns 'isSuccessful=false' with error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function updateComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer;

    /**
     * Specification:
     * - Expects customer id to be provided.
     * - Removes the provided comment by comment UUID in Persistence.
     * - Removes assigned tags in Persistence.
     * - Returns 'isSuccessful=true' with the up to date comment thread.
     * - Returns 'isSuccessful=false' with error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function removeComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer;

    /**
     * Specification:
     * - Expects owner id and type to be provided.
     * - Creates and returns a copy of the provided comment thread.
     * - Keeps only those comments which match the provided CommentFilter criteria.
     * - Returns 'isSuccessful=true' with the up to date comment thread.
     * - Returns 'isSuccessful=false' with error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $commentFilterTransfer
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function duplicateCommentThread(CommentFilterTransfer $commentFilterTransfer, CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer;

    /**
     * Specification:
     * - Adds the provided comment tag by comment UUID in Persistence.
     * - Returns 'isSuccessful=true' with the up to date comment thread.
     * - Returns 'isSuccessful=false' with error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTagRequestTransfer $commentTagRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function addCommentTag(CommentTagRequestTransfer $commentTagRequestTransfer): CommentThreadResponseTransfer;

    /**
     * Specification:
     * - Removes the provided comment tag by comment UUID in Persistence.
     * - Returns 'isSuccessful=true' with the up to date comment thread.
     * - Returns 'isSuccessful=false' with error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTagRequestTransfer $commentTagRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function removeCommentTag(CommentTagRequestTransfer $commentTagRequestTransfer): CommentThreadResponseTransfer;
}
