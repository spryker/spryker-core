<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Comment;

use Generated\Shared\Transfer\CommentRequestTransfer;
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
     * - Makes Zed request.
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
     * - Makes Zed request.
     * - Updates the provided comment tags by comment UUID in Persistence.
     * - Generates missing tags if Persistence.
     * - Returns 'isSuccessful=true' with the up to date comment thread.
     * - Returns 'isSuccessful=false' with error messages otherwise.
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
     * - Makes Zed request.
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
     * - Makes Zed request.
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

    /**
     * Specification:
     * - Returns available tags for add/remove operations.
     *
     * @api
     *
     * @return string[]
     */
    public function getAvailableCommentTags(): array;
}
