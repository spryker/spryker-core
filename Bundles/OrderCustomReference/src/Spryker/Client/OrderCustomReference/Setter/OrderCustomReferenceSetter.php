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
use Spryker\Client\OrderCustomReference\OrderCustomReferenceConfig;

class OrderCustomReferenceSetter implements OrderCustomReferenceSetterInterface
{
    protected const GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH = 'order_custom_reference.validation.error.message_invalid_length';

    /**
     * @var \Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToPersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @var \Spryker\Client\OrderCustomReference\OrderCustomReferenceConfig
     */
    protected $orderCustomReferenceConfig;

    /**
     * @param \Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToPersistentCartClientInterface $persistentCartClient
     * @param \Spryker\Client\OrderCustomReference\OrderCustomReferenceConfig $orderCustomReferenceConfig
     */
    public function __construct(
        OrderCustomReferenceToPersistentCartClientInterface $persistentCartClient,
        OrderCustomReferenceConfig $orderCustomReferenceConfig
    ) {
        $this->persistentCartClient = $persistentCartClient;
        $this->orderCustomReferenceConfig = $orderCustomReferenceConfig;
    }

    /**
     * @param string $orderCustomReference
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setOrderCustomReference(
        string $orderCustomReference,
        QuoteTransfer $quoteTransfer
    ): QuoteResponseTransfer {
        $quoteTransfer->requireIdQuote()
            ->requireCustomer();

        if (!$this->isOrderCustomReferenceLengthValid($orderCustomReference)) {
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

    /**
     * @param string|null $orderCustomReference
     *
     * @return bool
     */
    protected function isOrderCustomReferenceLengthValid(?string $orderCustomReference): bool
    {
        if (!$orderCustomReference) {
            return true;
        }

        return mb_strlen($orderCustomReference) <= $this->orderCustomReferenceConfig->getOrderCustomReferenceMaxLength();
    }
}
