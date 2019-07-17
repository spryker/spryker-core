<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence;

use Generated\Shared\Transfer\CommentTagTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;

interface CommentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    public function createCommentThread(CommentThreadTransfer $commentThreadTransfer): CommentThreadTransfer;

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function createComment(CommentTransfer $commentTransfer): CommentTransfer;

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function updateComment(CommentTransfer $commentTransfer): CommentTransfer;

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return void
     */
    public function removeComment(CommentTransfer $commentTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return void
     */
    public function addCommentTagsToComment(CommentTransfer $commentTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return void
     */
    public function removeCommentTagsFromComment(CommentTransfer $commentTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CommentTagTransfer $commentTagTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTagTransfer
     */
    public function createCommentTag(CommentTagTransfer $commentTagTransfer): CommentTagTransfer;
}
