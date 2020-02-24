<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OrderCustomReference;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\OrderCustomReference\OrderCustomReferenceFactory getFactory()
 */
class OrderCustomReferenceClient extends AbstractClient implements OrderCustomReferenceClientInterface
{
    protected const ORDER_CUSTOM_REFERENCE_MAX_LENGTH = 255;

    protected const GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH = 'order_custom_reference.validation.error.message_invalid_length';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $orderCustomReference
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setOrderCustomReference(
        QuoteTransfer $quoteTransfer,
        string $orderCustomReference
    ): QuoteResponseTransfer {
        if (!$this->isOrderCustomReferenceLengthValid($orderCustomReference)) {
            return $this->createQuoteResponseTransferWithError(static::GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH);
        }

        return $this->getFactory()
            ->createOrderCustomReferenceSetter()
            ->setOrderCustomReference($quoteTransfer, $orderCustomReference);
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

        return mb_strlen($orderCustomReference) <= static::ORDER_CUSTOM_REFERENCE_MAX_LENGTH;
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
