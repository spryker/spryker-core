<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Comment\Zed;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentResponseTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
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
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThread(CommentRequestTransfer $commentRequestTransfer): ?CommentThreadTransfer
    {
        /** @var \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer */
        $commentThreadTransfer = $this->zedRequestClient->call(
            '/comment/gateway/find-comment-thread',
            $commentRequestTransfer
        );

        return $commentThreadTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function addComment(CommentRequestTransfer $commentRequestTransfer): CommentResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CommentResponseTransfer $commentResponseTransfer */
        $commentResponseTransfer = $this->zedRequestClient->call(
            '/comment/gateway/add-comment',
            $commentRequestTransfer
        );

        return $commentResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function updateComment(CommentRequestTransfer $commentRequestTransfer): CommentResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CommentResponseTransfer $commentResponseTransfer */
        $commentResponseTransfer = $this->zedRequestClient->call(
            '/comment/gateway/update-comment',
            $commentRequestTransfer
        );

        return $commentResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function updateCommentTags(CommentRequestTransfer $commentRequestTransfer): CommentResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CommentResponseTransfer $commentResponseTransfer */
        $commentResponseTransfer = $this->zedRequestClient->call(
            '/comment/gateway/update-comment-tags',
            $commentRequestTransfer
        );

        return $commentResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function removeComment(CommentRequestTransfer $commentRequestTransfer): CommentResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CommentResponseTransfer $commentResponseTransfer */
        $commentResponseTransfer = $this->zedRequestClient->call(
            '/comment/gateway/remove-comment',
            $commentRequestTransfer
        );

        return $commentResponseTransfer;
    }
}
