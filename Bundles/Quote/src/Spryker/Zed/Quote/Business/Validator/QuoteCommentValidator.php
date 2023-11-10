<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Validator;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentValidationResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Zed\Quote\Business\Model\QuoteReaderInterface;
use Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface;

/**
 * @deprecated Will be removed without replacement.
 */
class QuoteCommentValidator implements QuoteCommentValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_VALIDATION_ACCESS_DENIED = 'comment.validation.error.access_denied';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_VALIDATION_CUSTOMER_NOT_SET = 'comment.validation.error.customer_not_set';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_VALIDATION_OWNER_NOT_SET = 'comment.validation.error.owner_not_set';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAM_OWNER = '%owner%';

    /**
     * @var \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Quote\Business\Model\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @param \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Quote\Business\Model\QuoteReaderInterface $quoteReader
     */
    public function __construct(QuoteToStoreFacadeInterface $storeFacade, QuoteReaderInterface $quoteReader)
    {
        $this->storeFacade = $storeFacade;
        $this->quoteReader = $quoteReader;
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
        if (!$this->isCustomerProvided($commentRequestTransfer)) {
            $messageTransfer = $this->createMessageTransfer(static::GLOSSARY_KEY_COMMENT_VALIDATION_CUSTOMER_NOT_SET);

            return $commentValidationResponseTransfer
                ->addMessage($messageTransfer)
                ->setIsSuccessful(false);
        }

        if ($commentRequestTransfer->getOwnerId() === null) {
            $messageTransfer = $this->createMessageTransfer(
                static::GLOSSARY_KEY_COMMENT_VALIDATION_OWNER_NOT_SET,
                [static::GLOSSARY_KEY_PARAM_OWNER => $commentRequestTransfer->getOwnerTypeOrFail()],
            );

            return $commentValidationResponseTransfer
                ->addMessage($messageTransfer)
                ->setIsSuccessful(false);
        }

        $quoteResponseTransfer = $this->quoteReader->findQuoteById($commentRequestTransfer->getOwnerIdOrFail());
        if (
            !$quoteResponseTransfer->getIsSuccessful() ||
            !$this->isQuoteOwnedByCustomer($quoteResponseTransfer, $commentRequestTransfer->getCommentOrFail()->getCustomerOrFail())
        ) {
            $messageTransfer = $this->createMessageTransfer(static::GLOSSARY_KEY_COMMENT_VALIDATION_ACCESS_DENIED);

            return $commentValidationResponseTransfer
                ->addMessage($messageTransfer)
                ->setIsSuccessful(false);
        }

        return $commentValidationResponseTransfer->setIsSuccessful(
            $commentValidationResponseTransfer->getIsSuccessful() ?? true,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return bool
     */
    protected function isCustomerProvided(CommentRequestTransfer $commentRequestTransfer): bool
    {
        return $commentRequestTransfer->getComment()
            && $commentRequestTransfer->getCommentOrFail()->getCustomer()
            && $commentRequestTransfer->getCommentOrFail()->getCustomer()->getCustomerReference();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isQuoteOwnedByCustomer(
        QuoteResponseTransfer $quoteResponseTransfer,
        CustomerTransfer $customerTransfer
    ): bool {
        return $quoteResponseTransfer->getQuoteTransferOrFail()->getCustomerReferenceOrFail() === $customerTransfer->getCustomerReferenceOrFail();
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
