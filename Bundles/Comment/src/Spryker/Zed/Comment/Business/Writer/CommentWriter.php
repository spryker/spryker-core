<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business\Writer;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface;
use Spryker\Zed\Comment\Persistence\CommentRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CommentWriter implements CommentWriterInterface
{
    use TransactionTrait;

    protected const GLOSSARY_KEY_COMMENT_THREAD_NOT_FOUND = 'comment.validation.error.comment_thread_not_found';
    protected const GLOSSARY_KEY_COMMENT_IN_THREAD_NOT_FOUND = 'comment.validation.error.comment_in_thread_not_found';

    /**
     * @var \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface
     */
    protected $commentEntityManager;

    /**
     * @var \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface
     */
    protected $commentRepository;

    /**
     * @param \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface $commentEntityManager
     * @param \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface $commentRepository
     */
    public function __construct(
        CommentEntityManagerInterface $commentEntityManager,
        CommentRepositoryInterface $commentRepository
    ) {
        $this->commentEntityManager = $commentEntityManager;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function addComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($commentRequestTransfer) {
            return $this->executeAddCommentTransaction($commentRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function updateComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($commentRequestTransfer) {
            return $this->executeUpdateCommentTransaction($commentRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function executeAddCommentTransaction(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        $commentThreadTransfer = $this->commentRepository->findCommentThread($commentRequestTransfer);

        if (!$commentThreadTransfer) {
            $commentThreadTransfer = $this->createCommentThreadTransfer($commentRequestTransfer);
        }

        $commentTransfer = $this->createCommentTransfer($commentRequestTransfer->getComment(), $commentThreadTransfer);
        $commentThreadTransfer->addComment($commentTransfer);

        return (new CommentThreadResponseTransfer())
            ->setIsSuccessful(true)
            ->setCommentThread($commentThreadTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function executeUpdateCommentTransaction(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        $commentThreadTransfer = $this->commentRepository->findCommentThread($commentRequestTransfer);

        if (!$commentThreadTransfer) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_COMMENT_THREAD_NOT_FOUND);
        }

        $this->updateCommentTransfer($commentRequestTransfer->getComment(), $commentThreadTransfer);

        return (new CommentThreadResponseTransfer())
            ->setIsSuccessful(true)
            ->setCommentThread($commentThreadTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    protected function createCommentThreadTransfer(CommentRequestTransfer $commentRequestTransfer): CommentThreadTransfer
    {
        $commentRequestTransfer
            ->requireOwnerId()
            ->requireOwnerType();

        $commentThreadTransfer = (new CommentThreadTransfer())
            ->setOwnerId($commentRequestTransfer->getOwnerId())
            ->setOwnerType($commentRequestTransfer->getOwnerType());

        return $this->commentEntityManager->createCommentThread($commentThreadTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    protected function createCommentTransfer(
        CommentTransfer $commentTransfer,
        CommentThreadTransfer $commentThreadTransfer
    ): CommentTransfer {
        $commentThreadTransfer->requireIdCommentThread();
        $commentTransfer
            ->requireMessage()
            ->requireCustomer();

        $commentTransfer->setFkCommentThread($commentThreadTransfer->getIdCommentThread());

        return $this->commentEntityManager->createComment($commentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $newCommentTransfer
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    protected function updateCommentTransfer(
        CommentTransfer $newCommentTransfer,
        CommentThreadTransfer $commentThreadTransfer
    ): CommentTransfer {
        foreach ($commentThreadTransfer->getComments() as $commentTransfer) {
            if ($commentTransfer->getUuid() !== $newCommentTransfer->getUuid()) {
                continue;
            }

            if ($commentTransfer->getMessage() === $newCommentTransfer->getMessage()) {
                return $commentTransfer;
            }

            return $this->commentEntityManager->updateComment($newCommentTransfer);
        }

        return $newCommentTransfer;
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function getErrorResponse(string $message): CommentThreadResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new CommentThreadResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
