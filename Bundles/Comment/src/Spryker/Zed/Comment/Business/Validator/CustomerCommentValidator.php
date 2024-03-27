<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business\Validator;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentValidationResponseTransfer;
use Generated\Shared\Transfer\CustomerCriteriaFilterTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Comment\Dependency\Facade\CommentToCustomerFacadeInterface;
use Spryker\Zed\Comment\Persistence\CommentRepositoryInterface;

class CustomerCommentValidator implements CustomerCommentValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CUSTOMER_NOT_FOUND = 'comment.validation.error.customer_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_ACCESS_DENIED = 'comment.validation.error.access_denied';

    /**
     * @var \Spryker\Zed\Comment\Dependency\Facade\CommentToCustomerFacadeInterface
     */
    protected CommentToCustomerFacadeInterface $customerFacade;

    /**
     * @var \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface
     */
    protected CommentRepositoryInterface $commentRepository;

    /**
     * @param \Spryker\Zed\Comment\Dependency\Facade\CommentToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface $commentRepository
     */
    public function __construct(
        CommentToCustomerFacadeInterface $customerFacade,
        CommentRepositoryInterface $commentRepository
    ) {
        $this->customerFacade = $customerFacade;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentValidationResponseTransfer
     */
    public function validateCommentAuthor(CommentRequestTransfer $commentRequestTransfer): CommentValidationResponseTransfer
    {
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer())->setIsSuccessful(false);

        $idCustomer = $commentRequestTransfer->getCommentOrFail()->getCustomerOrFail()->getIdCustomerOrFail();
        $customerTransfer = $this->customerFacade
            ->getCustomerCollectionByCriteria((new CustomerCriteriaFilterTransfer())->addIdCustomer($idCustomer))
            ->getCustomers()
            ->getIterator()
            ->current();

        if (!$customerTransfer) {
            return $commentValidationResponseTransfer->addMessage(
                $this->createMessageTransfer(static::GLOSSARY_KEY_CUSTOMER_NOT_FOUND),
            );
        }

        $idComment = $commentRequestTransfer->getCommentOrFail()->getIdComment();
        if ($idComment && !$this->commentRepository->isCustomerCommentAuthor($idCustomer, $idComment)) {
            return $commentValidationResponseTransfer->addMessage(
                $this->createMessageTransfer(static::GLOSSARY_KEY_COMMENT_ACCESS_DENIED),
            );
        }

        return $commentValidationResponseTransfer->setIsSuccessful(true);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(string $message): MessageTransfer
    {
        return (new MessageTransfer())->setValue($message);
    }
}
