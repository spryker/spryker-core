<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business;

use Generated\Shared\Transfer\CommentRequestTransfer;
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
     * - Creates comment thread if it does not exist in Persistence yet.
     * - Expects CommentTransfer with CustomerTransfer and message.
     * - Generates comment thread UUID when it is missing.
     * - Generates missing tags in Persistence.
     * - Persists provided comment for the comment thread.
     * - Returns with the up to date comment thread.
     * - Returns with error message(s) in case of error.
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
     * - Updates the provided comment by comment UUID in Persistence.
     * - Expects CommentTransfer with CustomerTransfer and message.
     * - Generates missing tags if Persistence.
     * - Returns with the up to date comment thread.
     * - Returns with error message(s) in case of error.
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
     * - Updates the provided comment tags by comment UUID in Persistence.
     * - Expects CommentTransfer with CustomerTransfer.
     * - Generates missing tags if Persistence.
     * - Returns with the up to date comment thread.
     * - Returns with error message(s) in case of error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function updateCommentTags(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer;

    /**
     * Specification:
     * - Removes the provided comment by comment UUID in Persistence.
     * - Removes assigned tags in Persistence.
     * - Returns with the up to date comment thread.
     * - Returns with error message(s) in case of error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function removeComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer;
}
