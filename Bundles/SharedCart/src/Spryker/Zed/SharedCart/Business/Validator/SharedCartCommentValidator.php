<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\Validator;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentValidationResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface;

/**
 * @deprecated Will be removed without replacement.
 */
class SharedCartCommentValidator implements SharedCartCommentValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_VALIDATION_ACCESS_DENIED = 'comment.validation.error.access_denied';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_VALIDATION_COMPANY_USER_NOT_SET = 'comment.validation.error.company_user_not_set';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_VALIDATION_OWNER_NOT_SET = 'comment.validation.error.owner_not_set';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAM_OWNER = '%owner%';

    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface
     */
    protected $sharedCartRepository;

    /**
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface $sharedCartRepository
     */
    public function __construct(SharedCartRepositoryInterface $sharedCartRepository)
    {
        $this->sharedCartRepository = $sharedCartRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     * @param \Generated\Shared\Transfer\CommentValidationResponseTransfer $commentValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CommentValidationResponseTransfer
     */
    public function validate(
        CommentRequestTransfer $commentRequestTransfer,
        CommentValidationResponseTransfer $commentValidationResponseTransfer
    ): CommentValidationResponseTransfer {
        if (!$this->isCompanyUserProvided($commentRequestTransfer)) {
            return $commentValidationResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage($this->createMessageTransfer(static::GLOSSARY_KEY_COMMENT_VALIDATION_COMPANY_USER_NOT_SET));
        }

        if ($commentRequestTransfer->getOwnerId() === null) {
            return $commentValidationResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage($this->createMessageTransfer(
                    static::GLOSSARY_KEY_COMMENT_VALIDATION_OWNER_NOT_SET,
                    [static::GLOSSARY_KEY_PARAM_OWNER => $commentRequestTransfer->getOwnerTypeOrFail()],
                ));
        }

        $companyUserTransfer = $commentRequestTransfer->getCommentOrFail()->getCustomerOrFail()->getCompanyUserTransferOrFail();
        $shareDetailTransfer = $this->sharedCartRepository->findShareDetailByIdQuoteAndIdCompanyUser(
            $commentRequestTransfer->getOwnerIdOrFail(),
            $companyUserTransfer->getIdCompanyUserOrFail(),
        );

        if ($shareDetailTransfer) {
            return $commentValidationResponseTransfer->setIsSuccessful(
                $commentValidationResponseTransfer->getIsSuccessful() ?? true,
            );
        }

        return $commentValidationResponseTransfer
            ->setIsSuccessful(false)
            ->addMessage($this->createMessageTransfer(static::GLOSSARY_KEY_COMMENT_VALIDATION_ACCESS_DENIED));
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return bool
     */
    protected function isCompanyUserProvided(CommentRequestTransfer $commentRequestTransfer): bool
    {
        return $commentRequestTransfer->getComment()
            && $commentRequestTransfer->getCommentOrFail()->getCustomer()
            && $commentRequestTransfer->getCommentOrFail()->getCustomerOrFail()->getCompanyUserTransfer()
            && $commentRequestTransfer->getCommentOrFail()->getCustomerOrFail()->getCompanyUserTransferOrFail()->getIdCompanyUser();
    }

    /**
     * @param string $message
     * @param array<string, string> $parameters
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(string $message, array $parameters = []): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue($message)
            ->setParameters($parameters);
    }
}
