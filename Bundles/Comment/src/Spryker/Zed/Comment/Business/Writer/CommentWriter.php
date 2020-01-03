<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Comment\Business\Reader\CommentThreadReaderInterface;
use Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface;
use Spryker\Zed\Comment\Persistence\CommentRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CommentWriter implements CommentWriterInterface
{
    use TransactionTrait;

    protected const COMMENT_MESSAGE_MIN_LENGTH = 1;
    protected const COMMENT_MESSAGE_MAX_LENGTH = 5000;

    protected const GLOSSARY_KEY_COMMENT_NOT_FOUND = 'comment.validation.error.comment_not_found';
    protected const GLOSSARY_KEY_COMMENT_THREAD_NOT_FOUND = 'comment.validation.error.comment_thread_not_found';
    protected const GLOSSARY_KEY_COMMENT_ACCESS_DENIED = 'comment.validation.error.access_denied';
    protected const GLOSSARY_KEY_COMMENT_INVALID_MESSAGE_LENGTH = 'comment.validation.error.invalid_message_length';

    /**
     * @var \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface
     */
    protected $commentEntityManager;

    /**
     * @var \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface
     */
    protected $commentRepository;

    /**
     * @var \Spryker\Zed\Comment\Business\Reader\CommentThreadReaderInterface
     */
    protected $commentThreadReader;

    /**
     * @var \Spryker\Zed\Comment\Business\Writer\CommentThreadWriterInterface
     */
    protected $commentThreadWriter;

    /**
     * @param \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface $commentEntityManager
     * @param \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface $commentRepository
     * @param \Spryker\Zed\Comment\Business\Reader\CommentThreadReaderInterface $commentThreadReader
     * @param \Spryker\Zed\Comment\Business\Writer\CommentThreadWriterInterface $commentThreadWriter
     */
    public function __construct(
        CommentEntityManagerInterface $commentEntityManager,
        CommentRepositoryInterface $commentRepository,
        CommentThreadReaderInterface $commentThreadReader,
        CommentThreadWriterInterface $commentThreadWriter
    ) {
        $this->commentEntityManager = $commentEntityManager;
        $this->commentRepository = $commentRepository;
        $this->commentThreadReader = $commentThreadReader;
        $this->commentThreadWriter = $commentThreadWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function addComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        $commentRequestTransfer
            ->requireOwnerId()
            ->requireOwnerType()
            ->requireComment()
            ->getComment()
                ->requireMessage()
                ->requireCustomer()
                ->getCustomer()
                    ->requireIdCustomer();

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
        $commentRequestTransfer
            ->requireComment()
            ->getComment()
                ->requireUuid()
                ->requireMessage()
                ->requireCustomer()
                ->getCustomer()
                    ->requireIdCustomer();

        return $this->getTransactionHandler()->handleTransaction(function () use ($commentRequestTransfer) {
            return $this->executeUpdateCommentTransaction($commentRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function removeComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        $commentRequestTransfer
            ->requireComment()
            ->getComment()
                ->requireUuid()
                ->requireCustomer()
                ->getCustomer()
                    ->requireIdCustomer();

        return $this->getTransactionHandler()->handleTransaction(function () use ($commentRequestTransfer) {
            return $this->executeRemoveCommentTransaction($commentRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function executeAddCommentTransaction(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        $commentTransfer = $commentRequestTransfer->getComment();
        $commentTransfer
            ->setMessage(trim($commentTransfer->getMessage()))
            ->setIsUpdated(false);

        $commentThreadResponseTransfer = $this->validateCommentMessage($commentTransfer);

        if (!$commentThreadResponseTransfer->getIsSuccessful()) {
            return $commentThreadResponseTransfer;
        }

        $commentThreadTransfer = $this->getCommentThread($commentRequestTransfer);
        $commentTransfer->setIdCommentThread($commentThreadTransfer->getIdCommentThread());

        $this->commentEntityManager->createComment($commentTransfer);
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
        $commentTransfer = $this->commentRepository->findCommentByUuid($commentRequestTransfer->getComment());

        $commentThreadResponseTransfer = $this->validateCommentRequest($commentRequestTransfer, $commentTransfer);

        if (!$commentThreadResponseTransfer->getIsSuccessful()) {
            return $commentThreadResponseTransfer;
        }

        $commentTransfer
            ->setMessage(trim($commentRequestTransfer->getComment()->getMessage()))
            ->setCommentTags($commentRequestTransfer->getComment()->getCommentTags())
            ->setIsUpdated(true);

        $commentThreadResponseTransfer = $this->validateCommentMessage($commentTransfer);

        if (!$commentThreadResponseTransfer->getIsSuccessful()) {
            return $commentThreadResponseTransfer;
        }

        $commentTransfer = $this->commentEntityManager->updateComment($commentTransfer);

        return $this->createCommentThreadResponse($commentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function executeRemoveCommentTransaction(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        $commentTransfer = $this->commentRepository->findCommentByUuid($commentRequestTransfer->getComment());

        $commentThreadResponseTransfer = $this->validateCommentRequest($commentRequestTransfer, $commentTransfer);

        if (!$commentThreadResponseTransfer->getIsSuccessful()) {
            return $commentThreadResponseTransfer;
        }

        $commentTransfer->setCommentTags(new ArrayObject());
        $this->commentEntityManager->removeCommentTagsFromComment($commentTransfer);
        $this->commentEntityManager->removeComment($commentTransfer);

        return $this->createCommentThreadResponse($commentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    protected function getCommentThread(CommentRequestTransfer $commentRequestTransfer): CommentThreadTransfer
    {
        $commentThreadTransfer = $this->commentThreadReader->findCommentThreadByOwner($commentRequestTransfer);

        if ($commentThreadTransfer) {
            return $commentThreadTransfer;
        }

        return $this->commentThreadWriter->createCommentThread($commentRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     * @param \Generated\Shared\Transfer\CommentTransfer|null $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function validateCommentRequest(
        CommentRequestTransfer $commentRequestTransfer,
        ?CommentTransfer $commentTransfer
    ): CommentThreadResponseTransfer {
        if (!$commentTransfer) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_COMMENT_NOT_FOUND);
        }

        if ($commentTransfer->getCustomer()->getIdCustomer() !== $commentRequestTransfer->getComment()->getCustomer()->getIdCustomer()) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_COMMENT_ACCESS_DENIED);
        }

        return (new CommentThreadResponseTransfer())
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function validateCommentMessage(CommentTransfer $commentTransfer): CommentThreadResponseTransfer
    {
        $messageLength = mb_strlen($commentTransfer->getMessage());

        if ($messageLength < static::COMMENT_MESSAGE_MIN_LENGTH || $messageLength > static::COMMENT_MESSAGE_MAX_LENGTH) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_COMMENT_INVALID_MESSAGE_LENGTH);
        }

        return (new CommentThreadResponseTransfer())
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function createCommentThreadResponse(CommentTransfer $commentTransfer): CommentThreadResponseTransfer
    {
        $commentThreadTransfer = (new CommentThreadTransfer())
            ->setIdCommentThread($commentTransfer->getIdCommentThread());

        $commentThreadTransfer = $this->commentThreadReader->findCommentThreadById($commentThreadTransfer);

        return (new CommentThreadResponseTransfer())
            ->setIsSuccessful(true)
            ->setCommentThread($commentThreadTransfer);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function createErrorResponse(string $message): CommentThreadResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new CommentThreadResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
