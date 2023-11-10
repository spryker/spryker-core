<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\Validator;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentValidationResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToQuoteFacadeInterface;
use Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface;

class SharedCartCommentAccessValidator implements SharedCartCommentAccessValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_VALIDATION_ACCESS_DENIED = 'comment.validation.error.access_denied';

    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface
     */
    protected SharedCartRepositoryInterface $sharedCartRepository;

    /**
     * @var \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToQuoteFacadeInterface
     */
    protected SharedCartToQuoteFacadeInterface $quoteFacadeInterface;

    /**
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface $sharedCartRepository
     * @param \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToQuoteFacadeInterface $quoteFacadeInterface
     */
    public function __construct(
        SharedCartRepositoryInterface $sharedCartRepository,
        SharedCartToQuoteFacadeInterface $quoteFacadeInterface
    ) {
        $this->sharedCartRepository = $sharedCartRepository;
        $this->quoteFacadeInterface = $quoteFacadeInterface;
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
        $quoteTransfer = $this->quoteFacadeInterface
            ->getQuoteCollection((new QuoteCriteriaFilterTransfer())->addQuoteIds($commentRequestTransfer->getOwnerIdOrFail()))
            ->getQuotes()
            ->getIterator()
            ->current();

        if (!$quoteTransfer) {
            return $commentValidationResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage($this->createMessageTransfer(static::GLOSSARY_KEY_COMMENT_VALIDATION_ACCESS_DENIED));
        }

        if ($this->isQuoteOwnedByCustomer($commentRequestTransfer, $quoteTransfer)) {
            return $commentValidationResponseTransfer->setIsSuccessful($commentValidationResponseTransfer->getIsSuccessful() ?? true);
        }

        if (!$this->isCompanyUserProvided($commentRequestTransfer)) {
            return $commentValidationResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage($this->createMessageTransfer(static::GLOSSARY_KEY_COMMENT_VALIDATION_ACCESS_DENIED));
        }

        $shareDetailTransfer = $this->sharedCartRepository->findShareDetailByIdQuoteAndIdCompanyUser(
            $commentRequestTransfer->getOwnerIdOrFail(),
            $commentRequestTransfer->getCommentOrFail()->getCustomerOrFail()->getCompanyUserTransferOrFail()->getIdCompanyUserOrFail(),
        );

        if (!$shareDetailTransfer) {
            return $commentValidationResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage($this->createMessageTransfer(static::GLOSSARY_KEY_COMMENT_VALIDATION_ACCESS_DENIED));
        }

        return $commentValidationResponseTransfer->setIsSuccessful($commentValidationResponseTransfer->getIsSuccessful() ?? true);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return bool
     */
    protected function isCompanyUserProvided(CommentRequestTransfer $commentRequestTransfer): bool
    {
        return $commentRequestTransfer->getCommentOrFail()->getCustomerOrFail()->getCompanyUserTransfer()
            && $commentRequestTransfer->getCommentOrFail()->getCustomerOrFail()->getCompanyUserTransferOrFail()->getIdCompanyUser();
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return bool
     */
    protected function isCustomerProvided(CommentRequestTransfer $commentRequestTransfer): bool
    {
        return $commentRequestTransfer->getCommentOrFail()->getCustomer()
            && $commentRequestTransfer->getCommentOrFail()->getCustomer()->getCustomerReference();
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

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteOwnedByCustomer(CommentRequestTransfer $commentRequestTransfer, QuoteTransfer $quoteTransfer): bool
    {
        return $commentRequestTransfer->getCommentOrFail()->getCustomerOrFail()->getCustomerReferenceOrFail() === $quoteTransfer->getCustomerReferenceOrFail();
    }
}
