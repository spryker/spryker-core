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

class CommentTagWriter implements CommentTagWriterInterface
{
    use TransactionTrait;

    protected const GLOSSARY_KEY_COMMENT_NOT_FOUND = 'comment.validation.error.comment_not_found';

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
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function saveCommentTags(CommentTransfer $commentTransfer): CommentTransfer
    {
        $expandedCommentTagTransfers = [];
        $commentTagMap = $this->mapCommentTagsByName($this->commentRepository->getAllCommentTags());

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

        $commentThreadTransfer = $this->commentReader->findCommentThreadById($commentThreadTransfer);

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
