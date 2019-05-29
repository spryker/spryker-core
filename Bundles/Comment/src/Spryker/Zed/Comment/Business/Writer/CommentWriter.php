<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business\Writer;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentResponseTransfer;
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
    protected const GLOSSARY_KEY_COMMENT_NOT_FOUND = 'comment.validation.error.comment_not_found';
    protected const GLOSSARY_KEY_COMMENT_ACCESS_DENIED = 'comment.validation.error.access_denied';

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
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function addComment(CommentRequestTransfer $commentRequestTransfer): CommentResponseTransfer
    {
        $commentRequestTransfer
            ->requireOwnerId()
            ->requireOwnerType()
            ->requireComment()
            ->getComment()
                ->requireMessage()
                ->requireCustomer();

        return $this->getTransactionHandler()->handleTransaction(function () use ($commentRequestTransfer) {
            return $this->executeAddCommentTransaction($commentRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function updateComment(CommentRequestTransfer $commentRequestTransfer): CommentResponseTransfer
    {
        $commentRequestTransfer
            ->requireComment()
            ->getComment()
                ->requireUuid()
                ->requireMessage()
                ->requireCustomer()
                ->getCustomer()
                    ->requireIdCustomer();

        $commentTransfer = $this->commentRepository
            ->findCommentByUuid($commentRequestTransfer->getComment()->getUuid());

        $commentResponseTransfer = $this->validateComment($commentRequestTransfer, $commentTransfer);

        if (!$commentResponseTransfer->getIsSuccessful()) {
            return $commentResponseTransfer;
        }

        $commentTransfer->setMessage($commentRequestTransfer->getComment()->getMessage());
        $commentTransfer = $this->commentEntityManager->updateComment($commentTransfer);

        return $commentResponseTransfer->setComment($commentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    public function removeComment(CommentRequestTransfer $commentRequestTransfer): CommentResponseTransfer
    {
        $commentRequestTransfer
            ->requireComment()
            ->getComment()
                ->requireUuid()
                ->requireCustomer()
                ->getCustomer()
                    ->requireIdCustomer();

        $commentTransfer = $this->commentRepository
            ->findCommentByUuid($commentRequestTransfer->getComment()->getUuid());

        $commentResponseTransfer = $this->validateComment($commentRequestTransfer, $commentTransfer);

        if (!$commentResponseTransfer->getIsSuccessful()) {
            return $commentResponseTransfer;
        }

        $this->commentEntityManager->removeComment($commentTransfer);

        return $commentResponseTransfer->setComment($commentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    protected function executeAddCommentTransaction(CommentRequestTransfer $commentRequestTransfer): CommentResponseTransfer
    {
        $commentThreadTransfer = $this->getCommentThread($commentRequestTransfer);

        $commentTransfer = $commentRequestTransfer->getComment();
        $commentTransfer->setIdCommentThread($commentThreadTransfer->getIdCommentThread());

        $commentTransfer = $this->commentEntityManager->createComment($commentTransfer);

        return (new CommentResponseTransfer())
            ->setIsSuccessful(true)
            ->setComment($commentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    protected function getCommentThread(CommentRequestTransfer $commentRequestTransfer): CommentThreadTransfer
    {
        $commentThreadTransfer = $this->commentRepository->findCommentThread($commentRequestTransfer);

        if ($commentThreadTransfer) {
            return $commentThreadTransfer;
        }

        $commentThreadTransfer = (new CommentThreadTransfer())
            ->setOwnerId($commentRequestTransfer->getOwnerId())
            ->setOwnerType($commentRequestTransfer->getOwnerType());

        return $this->commentEntityManager->createCommentThread($commentThreadTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     * @param \Generated\Shared\Transfer\CommentTransfer|null $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    protected function validateComment(
        CommentRequestTransfer $commentRequestTransfer,
        ?CommentTransfer $commentTransfer
    ): CommentResponseTransfer {
        if (!$commentTransfer) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_COMMENT_NOT_FOUND);
        }

        if ($commentTransfer->getCustomer()->getIdCustomer() !== $commentRequestTransfer->getComment()->getCustomer()->getIdCustomer()) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_COMMENT_ACCESS_DENIED);
        }

        return (new CommentResponseTransfer())
            ->setIsSuccessful(true);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\CommentResponseTransfer
     */
    protected function createErrorResponse(string $message): CommentResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new CommentResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
