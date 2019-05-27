<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence;

use DateTime;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CommentVersionTransfer;

interface CommentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function createComment(CommentTransfer $quoteRequestTransfer): CommentTransfer;

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function updateComment(CommentTransfer $quoteRequestTransfer): CommentTransfer;

    /**
     * @param \Generated\Shared\Transfer\CommentVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CommentVersionTransfer
     */
    public function createCommentVersion(CommentVersionTransfer $quoteRequestVersionTransfer): CommentVersionTransfer;

    /**
     * @param \Generated\Shared\Transfer\CommentVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CommentVersionTransfer
     */
    public function updateCommentVersion(CommentVersionTransfer $quoteRequestVersionTransfer): CommentVersionTransfer;

    /**
     * @param \DateTime $validUntil
     *
     * @return void
     */
    public function closeOutdatedComments(DateTime $validUntil): void;

    /**
     * @param string $quoteRequestReference
     * @param string $fromStatus
     * @param string $toStatus
     *
     * @return bool
     */
    public function updateCommentStatus(string $quoteRequestReference, string $fromStatus, string $toStatus): bool;
}
