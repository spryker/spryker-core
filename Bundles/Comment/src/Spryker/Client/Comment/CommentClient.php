<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Comment;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTagRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;
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
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function addComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
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
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function updateComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
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
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function updateCommentTags(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
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
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function removeComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        return $this->getZedStub()->removeComment($commentRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTagRequestTransfer $commentTagRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function addCommentTag(CommentTagRequestTransfer $commentTagRequestTransfer): CommentThreadResponseTransfer
    {
        return $this->getFactory()
            ->createCommentTagWriter()
            ->addCommentTag($commentTagRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTagRequestTransfer $commentTagRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function removeCommentTag(CommentTagRequestTransfer $commentTagRequestTransfer): CommentThreadResponseTransfer
    {
        return $this->getFactory()
            ->createCommentTagWriter()
            ->removeCommentTag($commentTagRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getAvailableCommentTags(): array
    {
        return $this->getFactory()
            ->getModuleConfig()
            ->getAvailableCommentTags();
    }

    /**
     * @return \Spryker\Client\Comment\Zed\CommentStubInterface
     */
    protected function getZedStub(): CommentStubInterface
    {
        return $this->getFactory()->createZedCommentStub();
    }
}
