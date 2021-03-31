<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentsRequestTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Spryker\Zed\Comment\Persistence\CommentRepositoryInterface;

class CommentThreadReader implements CommentThreadReaderInterface
{
    /**
     * @var \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface
     */
    protected $commentRepository;

    /**
     * @param \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface $commentRepository
     */
    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThreadByOwner(CommentRequestTransfer $commentRequestTransfer): ?CommentThreadTransfer
    {
        $commentThreadTransfer = $this->commentRepository->findCommentThread($commentRequestTransfer);

        if ($commentThreadTransfer === null) {
            return null;
        }

        $commentTransfers = $this->commentRepository->findCommentsByCommentThread($commentThreadTransfer);
        $commentThreadTransfer->setComments(new ArrayObject($commentTransfers));

        return $commentThreadTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentsRequestTransfer $commentsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer[]
     */
    public function getCommentThreads(CommentsRequestTransfer $commentsRequestTransfer): array
    {
        $commentThreadTransfers = $this->commentRepository->getCommentThreads($commentsRequestTransfer);

        if ($commentThreadTransfers === []) {
            return [];
        }

        $threadIds = $this->collectThreadIds($commentThreadTransfers);
        $commentTransfers = $this->commentRepository->getCommentsByCommentThreadIds($threadIds);

        return $this->mapCommentsToThreads($commentThreadTransfers, $commentTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThreadById(CommentThreadTransfer $commentThreadTransfer): ?CommentThreadTransfer
    {
        $commentThreadTransfer = $this->commentRepository->findCommentThreadById($commentThreadTransfer);

        if ($commentThreadTransfer === null) {
            return null;
        }

        $commentTransfers = $this->commentRepository->findCommentsByCommentThread($commentThreadTransfer);
        $commentThreadTransfer->setComments(new ArrayObject($commentTransfers));

        return $commentThreadTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer[] $commentThreadTransfers
     *
     * @return int[]
     */
    protected function collectThreadIds(array $commentThreadTransfers): array
    {
        $threadIds = [];
        foreach ($commentThreadTransfers as $commentThreadTransfer) {
            $threadIds[] = $commentThreadTransfer->getIdCommentThread();
        }

        return $threadIds;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer[] $commentThreadTransfers
     * @param \Generated\Shared\Transfer\CommentTransfer[] $commentTransfers
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer[]
     */
    protected function mapCommentsToThreads(array $commentThreadTransfers, array $commentTransfers): array
    {
        foreach ($commentTransfers as $commentTransfer) {
            $threadId = $commentTransfer->getIdCommentThread();
            if (!isset($commentThreadTransfers[$threadId])) {
                continue;
            }

            $commentThreadTransfers[$threadId]->addComment($commentTransfer);
        }

        return $commentThreadTransfers;
    }
}
