<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Comment;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentResponseTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Spryker\Client\Comment\Zed\CommentStubInterface;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Comment\CommentFactory getFactory()
 */
class CommentClient extends AbstractClient implements CommentClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThread(CommentRequestTransfer $commentRequestTransfer): ?CommentThreadTransfer
    {
        return $this->getZedStub()->findCommentThread($commentRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function addComment(CommentRequestTransfer $commentRequestTransfer): CommentResponseTransfer
    {
        return $this->getZedStub()->addComment($commentRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function updateComment(CommentRequestTransfer $commentRequestTransfer): CommentResponseTransfer
    {
        return $this->getZedStub()->updateComment($commentRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function updateCommentTags(CommentRequestTransfer $commentRequestTransfer): CommentResponseTransfer
    {
        return $this->getZedStub()->updateCommentTags($commentRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function removeComment(CommentRequestTransfer $commentRequestTransfer): CommentResponseTransfer
    {
        return $this->getZedStub()->removeComment($commentRequestTransfer);
    }

    /**
     * @return \Spryker\Client\Comment\Zed\CommentStubInterface
     */
    protected function getZedStub(): CommentStubInterface
    {
        return $this->getFactory()->createCommentStub();
    }
}
