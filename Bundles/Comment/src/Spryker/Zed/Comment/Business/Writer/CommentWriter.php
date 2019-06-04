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
use Spryker\Zed\Comment\Business\Reader\CommentReaderInterface;
use Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface;
use Spryker\Zed\Comment\Persistence\CommentRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CommentWriter implements CommentWriterInterface
{
    use TransactionTrait;

    protected const GLOSSARY_KEY_COMMENT_NOT_FOUND = 'comment.validation.error.comment_not_found';
    protected const GLOSSARY_KEY_COMMENT_THREAD_NOT_FOUND = 'comment.validation.error.comment_thread_not_found';
    protected const GLOSSARY_KEY_COMMENT_ACCESS_DENIED = 'comment.validation.error.access_denied';
    protected const GLOSSARY_KEY_COMMENT_EMPTY_MESSAGE = 'comment.validation.error.empty_message';

    /**
     * @var \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface
     */
    protected $commentEntityManager;

    /**
     * @var \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface
     */
    protected $commentRepository;

    /**
     * @var \Spryker\Zed\Comment\Business\Reader\CommentReaderInterface
     */
    protected $commentReader;

    /**
     * @param \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface $commentEntityManager
     * @param \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface $commentRepository
     * @param \Spryker\Zed\Comment\Business\Reader\CommentReaderInterface $commentReader
     */
    public function __construct(
        CommentEntityManagerInterface $commentEntityManager,
        CommentRepositoryInterface $commentRepository,
        CommentReaderInterface $commentReader
    ) {
        $this->commentEntityManager = $commentEntityManager;
        $this->commentRepository = $commentRepository;
        $this->commentReader = $commentReader;
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
    public function updateCommentTags(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        $commentRequestTransfer
            ->requireComment()
            ->getComment()
                ->requireUuid();

        return $this->getTransactionHandler()->handleTransaction(function () use ($commentRequestTransfer) {
            return $this->executeUpdateCommentTagsTransaction($commentRequestTransfer);
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

        $commentTransfer = $this->commentEntityManager->createComment($commentTransfer);

        if ($commentTransfer->getTags()->count()) {
            $this->saveCommentTags($commentTransfer);
        }

        $commentThreadTransfer = $this->commentReader->findCommentThreadByOwner($commentRequestTransfer);

        if (!$commentThreadTransfer) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_COMMENT_THREAD_NOT_FOUND);
        }

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
        $commentTransfer = $this->commentRepository
            ->findCommentByUuid($commentRequestTransfer->getComment()->getUuid());

        $commentThreadResponseTransfer = $this->validateComment($commentRequestTransfer, $commentTransfer);

        if (!$commentThreadResponseTransfer->getIsSuccessful()) {
            return $commentThreadResponseTransfer;
        }

        $commentTransfer
            ->setMessage(trim($commentRequestTransfer->getComment()->getMessage()))
            ->setTags($commentRequestTransfer->getComment()->getTags())
            ->setIsUpdated(true);

        $commentThreadResponseTransfer = $this->validateCommentMessage($commentTransfer);

        if (!$commentThreadResponseTransfer->getIsSuccessful()) {
            return $commentThreadResponseTransfer;
        }

        $commentTransfer = $this->commentEntityManager->updateComment($commentTransfer);
        $this->saveCommentTags($commentTransfer);

        return $this->createCommentThreadResponse($commentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function executeUpdateCommentTagsTransaction(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        $commentTransfer = $this->commentRepository
            ->findCommentByUuid($commentRequestTransfer->getComment()->getUuid());

        if (!$commentTransfer) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_COMMENT_NOT_FOUND);
        }

        $commentTransfer->setTags($commentRequestTransfer->getComment()->getTags());
        $this->saveCommentTags($commentTransfer);

        return $this->createCommentThreadResponse($commentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function executeRemoveCommentTransaction(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        $commentTransfer = $this->commentRepository
            ->findCommentByUuid($commentRequestTransfer->getComment()->getUuid());

        $commentThreadResponseTransfer = $this->validateComment($commentRequestTransfer, $commentTransfer);

        if (!$commentThreadResponseTransfer->getIsSuccessful()) {
            return $commentThreadResponseTransfer;
        }

        $commentTransfer->setTags(new ArrayObject());
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
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function validateComment(
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
        if (!mb_strlen($commentTransfer->getMessage())) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_COMMENT_EMPTY_MESSAGE);
        }

        return (new CommentThreadResponseTransfer())
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    protected function saveCommentTags(CommentTransfer $commentTransfer): CommentTransfer
    {
        $expandedCommentTagTransfers = [];
        $commentTagMap = $this->mapCommentTagsByName($this->commentRepository->getCommentTags());

        foreach ($commentTransfer->getTags() as $commentTagTransfer) {
            if (!isset($commentTagMap[$commentTagTransfer->getName()])) {
                $commentTagMap[$commentTagTransfer->getName()] = $this->commentEntityManager->createCommentTag($commentTagTransfer);
            }

            $expandedCommentTagTransfers[] = $commentTagMap[$commentTagTransfer->getName()];
        }

        $commentTransfer->setTags(new ArrayObject($expandedCommentTagTransfers));

        $this->commentEntityManager->addCommentTagsToComment($commentTransfer);
        $this->commentEntityManager->removeCommentTagsFromComment($commentTransfer);

        return $commentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTagTransfer[] $commentTagTransfers
     *
     * @return \Generated\Shared\Transfer\CommentTagTransfer[]
     */
    protected function mapCommentTagsByName(array $commentTagTransfers): array
    {
        $commentTagMap = [];

        foreach ($commentTagTransfers as $commentTagTransfer) {
            $commentTagMap[$commentTagTransfer->getName()] = $commentTagTransfer;
        }

        return $commentTagMap;
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

        $commentThreadTransfer = $this->commentReader->getCommentThreadById($commentThreadTransfer);

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
