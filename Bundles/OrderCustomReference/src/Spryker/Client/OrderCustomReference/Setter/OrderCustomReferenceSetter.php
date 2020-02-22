<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OrderCustomReference\Setter;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToPersistentCartClientInterface;
use Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToQuoteClientInterface;
use Spryker\Client\OrderCustomReference\Validator\OrderCustomReferenceValidatorInterface;

class OrderCustomReferenceSetter implements OrderCustomReferenceSetterInterface
{
    protected const GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH = 'order_custom_reference.validation.error.message_invalid_length';

    /**
     * @var \Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToPersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @var \Spryker\Client\OrderCustomReference\Validator\OrderCustomReferenceValidatorInterface
     */
    protected $orderCustomReferenceValidator;

    /**
     * @var \Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToPersistentCartClientInterface $persistentCartClient
     * @param \Spryker\Client\OrderCustomReference\Validator\OrderCustomReferenceValidatorInterface $orderCustomReferenceValidator
     * @param \Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToQuoteClientInterface $quoteClient
     */
    public function __construct(
        OrderCustomReferenceToPersistentCartClientInterface $persistentCartClient,
        OrderCustomReferenceValidatorInterface $orderCustomReferenceValidator,
        OrderCustomReferenceToQuoteClientInterface $quoteClient
    ) {
        $this->persistentCartClient = $persistentCartClient;
        $this->orderCustomReferenceValidator = $orderCustomReferenceValidator;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param string|null $orderCustomReference
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setOrderCustomReference(?string $orderCustomReference): QuoteResponseTransfer
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        $quoteTransfer->requireIdQuote()
            ->requireCustomer();

        $isOrderCustomReferenceLengthValid = $this->orderCustomReferenceValidator
            ->isOrderCustomReferenceLengthValid($orderCustomReference);

        if (!$isOrderCustomReferenceLengthValid) {
            return $this->createQuoteResponseTransferWithError(static::GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH);
        }

        $quoteUpdateRequestTransfer = $this->createQuoteUpdateRequestTransfer(
            $quoteTransfer->setOrderCustomReference($orderCustomReference)
        );

        return $this->persistentCartClient->updateQuote($quoteUpdateRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteUpdateRequestTransfer
     */
    protected function createQuoteUpdateRequestTransfer(QuoteTransfer $quoteTransfer): QuoteUpdateRequestTransfer
    {
        $quoteUpdateRequestAttributesTransfer = (new QuoteUpdateRequestAttributesTransfer())
            ->setOrderCustomReference($quoteTransfer->getOrderCustomReference());

        return (new QuoteUpdateRequestTransfer())
            ->setIdQuote($quoteTransfer->getIdQuote())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createQuoteResponseTransferWithError(string $message): QuoteResponseTransfer
    {
        $quoteErrorTransfer = (new QuoteErrorTransfer())->setMessage($message);

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(false)
            ->addError($quoteErrorTransfer);
    }
}
