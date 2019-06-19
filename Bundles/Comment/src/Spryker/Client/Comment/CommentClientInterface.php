<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Comment;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentResponseTransfer;
use Generated\Shared\Transfer\CommentTagRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;

interface CommentClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Expects owner ID to be provided.
     * - Expects owner type to be provided.
     * - Expects comment message to be provided.
     * - Expects id customer to be provided.
     * - Creates comment thread if it does not exist in Persistence yet.
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
     * - Makes Zed request.
     * - Expects comment message to be provided.
     * - Expects id customer to be provided.
     * - Updates the provided comment by comment UUID in Persistence.
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
     * - Makes Zed request.
     * - Updates the provided comment tags by comment UUID in Persistence.
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
     * - Makes Zed request.
     * - Expects id customer to be provided.
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

    /**
     * Specification:
     * - Makes Zed request.
     * - Adds the provided comment tag by comment UUID in Persistence.
     * - Returns with the up to date comment.
     * - Returns with error message(s) in case of error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTagRequestTransfer $commentTagRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function addCommentTag(CommentTagRequestTransfer $commentTagRequestTransfer): CommentResponseTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Removes the provided comment tag by comment UUID in Persistence.
     * - Returns with the up to date comment.
     * - Returns with error message(s) in case of error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTagRequestTransfer $commentTagRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function removeCommentTag(CommentTagRequestTransfer $commentTagRequestTransfer): CommentResponseTransfer;
}
