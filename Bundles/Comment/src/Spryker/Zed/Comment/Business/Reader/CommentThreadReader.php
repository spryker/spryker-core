<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\CommentRequestTransfer;
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

        if (!$commentThreadTransfer) {
            return null;
        }

        $commentTransfers = $this->commentRepository->findCommentsByCommentThread($commentThreadTransfer);
        $commentThreadTransfer->setComments(new ArrayObject($commentTransfers));

        return $commentThreadTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThreadById(CommentThreadTransfer $commentThreadTransfer): ?CommentThreadTransfer
    {
        $commentThreadTransfer = $this->commentRepository->findCommentThreadById($commentThreadTransfer);

        if (!$commentThreadTransfer) {
            return null;
        }

        $commentTransfers = $this->commentRepository->findCommentsByCommentThread($commentThreadTransfer);
        $commentThreadTransfer->setComments(new ArrayObject($commentTransfers));

        return $commentThreadTransfer;
    }
}
