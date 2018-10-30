<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QuoteDiscountValidator;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

abstract class AbstractQuoteDiscountValidator implements QuoteDiscountValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    abstract public function validate(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     * @param string $errorCode
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function addError(MessageTransfer $message, string $errorCode, CheckoutResponseTransfer $checkoutResponseTransfer): void
    {
        $checkoutErrorTransfer = (new CheckoutErrorTransfer())
            ->setMessage($message->getValue())
            ->setErrorCode($errorCode);

        $checkoutResponseTransfer
            ->addError($checkoutErrorTransfer)
            ->setIsSuccess(false)
            ->setIsExternalRedirect(false);
    }
}
