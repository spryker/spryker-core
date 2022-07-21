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
use Spryker\Zed\Comment\Business\Reader\CommentThreadReaderInterface;
use Spryker\Zed\Comment\Business\Validator\CommentValidatorInterface;
use Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface;
use Spryker\Zed\Comment\Persistence\CommentRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CommentWriter implements CommentWriterInterface
{
    use TransactionTrait;

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
     * @var \Spryker\Zed\Comment\Business\Validator\CommentValidatorInterface
     */
    protected $commentValidator;

    /**
     * @param \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface $commentEntityManager
     * @param \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface $commentRepository
     * @param \Spryker\Zed\Comment\Business\Reader\CommentThreadReaderInterface $commentThreadReader
     * @param \Spryker\Zed\Comment\Business\Writer\CommentThreadWriterInterface $commentThreadWriter
     * @param \Spryker\Zed\Comment\Business\Validator\CommentValidatorInterface $commentValidator
     */
    public function __construct(
        CommentEntityManagerInterface $commentEntityManager,
        CommentRepositoryInterface $commentRepository,
        CommentThreadReaderInterface $commentThreadReader,
        CommentThreadWriterInterface $commentThreadWriter,
        CommentValidatorInterface $commentValidator
    ) {
        $this->commentEntityManager = $commentEntityManager;
        $this->commentRepository = $commentRepository;
        $this->commentThreadReader = $commentThreadReader;
        $this->commentThreadWriter = $commentThreadWriter;
        $this->commentValidator = $commentValidator;
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

        $commentValidationResponseTransfer = $this->commentValidator->validateCommentRequestOnCreate($commentRequestTransfer);
        if (!$commentValidationResponseTransfer->getIsSuccessfulOrFail()) {
            return (new CommentThreadResponseTransfer())
               ->setIsSuccessful(false)
               ->setMessages($commentValidationResponseTransfer->getMessages());
        }

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

        $commentTransfer = $this->commentRepository->findCommentByUuid($commentRequestTransfer->getComment());

        $commentValidationResponseTransfer = $this->commentValidator->validateCommentRequestOnUpdate($commentRequestTransfer, $commentTransfer);
        if (!$commentValidationResponseTransfer->getIsSuccessfulOrFail()) {
            return (new CommentThreadResponseTransfer())
                ->setIsSuccessful(false)
                ->setMessages($commentValidationResponseTransfer->getMessages());
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($commentRequestTransfer, $commentTransfer) {
            return $this->executeUpdateCommentTransaction($commentRequestTransfer, $commentTransfer);
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

        $commentTransfer = $this->commentRepository->findCommentByUuid($commentRequestTransfer->getComment());

        $commentValidationResponseTransfer = $this->commentValidator->validateCommentRequestOnUpdate($commentRequestTransfer, $commentTransfer);
        if (!$commentValidationResponseTransfer->getIsSuccessfulOrFail()) {
            return (new CommentThreadResponseTransfer())
                ->setIsSuccessful(false)
                ->setMessages($commentValidationResponseTransfer->getMessages());
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($commentTransfer) {
            return $this->executeRemoveCommentTransaction($commentTransfer);
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
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function executeUpdateCommentTransaction(
        CommentRequestTransfer $commentRequestTransfer,
        CommentTransfer $commentTransfer
    ): CommentThreadResponseTransfer {
        $commentTransfer
            ->setMessage(trim($commentRequestTransfer->getComment()->getMessage()))
            ->setCommentTags($commentRequestTransfer->getComment()->getCommentTags())
            ->setIsUpdated(true);

        $commentTransfer = $this->commentEntityManager->updateComment($commentTransfer);

        return $this->createCommentThreadResponse($commentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function executeRemoveCommentTransaction(
        CommentTransfer $commentTransfer
    ): CommentThreadResponseTransfer {
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
}
