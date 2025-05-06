<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Validator;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteValidator implements QuoteValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ORDER_NOT_BEING_AMENDED = 'sales_order_amendment_oms.validation.order_not_being_amended';

    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\OrderValidatorInterface $orderValidator
     */
    public function __construct(protected OrderValidatorInterface $orderValidator)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function validateQuotePreCheckout(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        if ($quoteTransfer->getAmendmentOrderReference() === null) {
            return true;
        }

        if (!$this->orderValidator->validateIsOrderBeingAmended($quoteTransfer->getAmendmentOrderReferenceOrFail())) {
            $checkoutResponseTransfer->addError($this->createCheckoutErrorTransfer(static::GLOSSARY_KEY_VALIDATION_ORDER_NOT_BEING_AMENDED));

            return false;
        }

        return true;
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer(string $message): CheckoutErrorTransfer
    {
        return (new CheckoutErrorTransfer())->setMessage($message);
    }
}
