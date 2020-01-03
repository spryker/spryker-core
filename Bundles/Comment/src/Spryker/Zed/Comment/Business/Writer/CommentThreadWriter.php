<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business\Writer;

use Generated\Shared\Transfer\CommentFilterTransfer;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface;
use Spryker\Zed\Comment\Persistence\CommentRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CommentThreadWriter implements CommentThreadWriterInterface
{
    use TransactionTrait;

    protected const GLOSSARY_KEY_COMMENT_THREAD_ALREADY_EXISTS = 'comment.validation.error.comment_thread_already_exists';

    /**
     * @var \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface
     */
    protected $commentEntityManager;

    /**
     * @var \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface
     */
    protected $commentRepository;

    /**
     * @var \Spryker\Zed\Comment\Business\Writer\CommentTagWriterInterface
     */
    protected $commentTagWriter;

    /**
     * @param \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface $commentEntityManager
     * @param \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface $commentRepository
     * @param \Spryker\Zed\Comment\Business\Writer\CommentTagWriterInterface $commentTagWriter
     */
    public function __construct(
        CommentEntityManagerInterface $commentEntityManager,
        CommentRepositoryInterface $commentRepository,
        CommentTagWriterInterface $commentTagWriter
    ) {
        $this->commentEntityManager = $commentEntityManager;
        $this->commentRepository = $commentRepository;
        $this->commentTagWriter = $commentTagWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    public function createCommentThread(CommentRequestTransfer $commentRequestTransfer): CommentThreadTransfer
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
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $commentFilterTransfer
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function duplicateCommentThread(CommentFilterTransfer $commentFilterTransfer, CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        $commentFilterTransfer
            ->requireOwnerId()
            ->requireOwnerType();

        $commentRequestTransfer
            ->requireOwnerId()
            ->requireOwnerType();

        return $this->getTransactionHandler()->handleTransaction(function () use ($commentFilterTransfer, $commentRequestTransfer) {
            return $this->executeDuplicateCommentThreadTransaction($commentFilterTransfer, $commentRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $commentFilterTransfer
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function executeDuplicateCommentThreadTransaction(
        CommentFilterTransfer $commentFilterTransfer,
        CommentRequestTransfer $commentRequestTransfer
    ): CommentThreadResponseTransfer {
        if ($this->commentRepository->findCommentThread($commentRequestTransfer)) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_COMMENT_THREAD_ALREADY_EXISTS);
        }

        $commentThreadTransfer = $this->createCommentThread($commentRequestTransfer);
        $commentTransfers = $this->commentRepository->getCommentsByFilter($commentFilterTransfer);

        foreach ($commentTransfers as $commentTransfer) {
            $commentThreadTransfer->addComment(
                $this->duplicateComment($commentTransfer, $commentThreadTransfer)
            );
        }

        return (new CommentThreadResponseTransfer())
            ->setIsSuccessful(true)
            ->setCommentThread($commentThreadTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    protected function duplicateComment(CommentTransfer $commentTransfer, CommentThreadTransfer $commentThreadTransfer): CommentTransfer
    {
        $duplicatedCommentTransfer = (new CommentTransfer())
            ->setCustomer($commentTransfer->getCustomer())
            ->setIdCommentThread($commentThreadTransfer->getIdCommentThread())
            ->setMessage($commentTransfer->getMessage())
            ->setIsUpdated($commentTransfer->getIsUpdated())
            ->setCommentTags($commentTransfer->getCommentTags());

        $duplicatedCommentTransfer = $this->commentEntityManager->createComment($duplicatedCommentTransfer);

        if ($duplicatedCommentTransfer->getCommentTags()->count()) {
            $this->commentTagWriter->saveCommentTags($duplicatedCommentTransfer);
        }

        return $duplicatedCommentTransfer;
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
