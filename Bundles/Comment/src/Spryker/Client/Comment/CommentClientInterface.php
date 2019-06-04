<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Comment;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;

interface CommentClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Creates comment thread if it does not exist in Persistence yet.
     * - Expects CommentTransfer with CustomerTransfer and message.
     * - Generates comment thread UUID when it is missing.
     * - Generates missing tags in Persistence.
     * - Persists provided comment for the comment thread.
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
     * - Updates the provided comment by comment UUID in Persistence.
     * - Expects CommentTransfer with CustomerTransfer and message.
     * - Generates missing tags if Persistence.
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
     * - Expects CommentTransfer with CustomerTransfer.
     * - Generates missing tags if Persistence.
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
     * - Removes the provided comment by comment UUID in Persistence.
     * - Removes assigned tags in Persistence.
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
