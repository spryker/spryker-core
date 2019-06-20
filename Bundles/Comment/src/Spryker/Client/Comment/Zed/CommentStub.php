<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Comment\Zed;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTagRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use Spryker\Client\Comment\Dependency\Client\CommentToZedRequestClientInterface;

class CommentStub implements CommentStubInterface
{
    /**
     * @var \Spryker\Client\Comment\Dependency\Client\CommentToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\Comment\Dependency\Client\CommentToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(CommentToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function addComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CommentThreadResponseTransfer $commentThreadResponseTransfer */
        $commentThreadResponseTransfer = $this->zedRequestClient->call(
            '/comment/gateway/add-comment',
            $commentRequestTransfer
        );

        return $commentThreadResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function updateComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CommentThreadResponseTransfer $commentThreadResponseTransfer */
        $commentThreadResponseTransfer = $this->zedRequestClient->call(
            '/comment/gateway/update-comment',
            $commentRequestTransfer
        );

        return $commentThreadResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function updateCommentTags(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CommentThreadResponseTransfer $commentThreadResponseTransfer */
        $commentThreadResponseTransfer = $this->zedRequestClient->call(
            '/comment/gateway/update-comment-tags',
            $commentRequestTransfer
        );

        return $commentThreadResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function removeComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CommentThreadResponseTransfer $commentThreadResponseTransfer */
        $commentThreadResponseTransfer = $this->zedRequestClient->call(
            '/comment/gateway/remove-comment',
            $commentRequestTransfer
        );

        return $commentThreadResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTagRequestTransfer $commentTagRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function addCommentTag(CommentTagRequestTransfer $commentTagRequestTransfer): CommentThreadResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CommentThreadResponseTransfer $commentThreadResponseTransfer */
        $commentThreadResponseTransfer = $this->zedRequestClient->call(
            '/comment/gateway/add-comment-tag',
            $commentTagRequestTransfer
        );

        return $commentThreadResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTagRequestTransfer $commentTagRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function removeCommentTag(CommentTagRequestTransfer $commentTagRequestTransfer): CommentThreadResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CommentThreadResponseTransfer $commentThreadResponseTransfer */
        $commentThreadResponseTransfer = $this->zedRequestClient->call(
            '/comment/gateway/remove-comment-tag',
            $commentTagRequestTransfer
        );

        return $commentThreadResponseTransfer;
    }
}
