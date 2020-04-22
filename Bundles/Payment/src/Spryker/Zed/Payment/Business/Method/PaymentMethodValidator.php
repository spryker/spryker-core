<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Method;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class PaymentMethodValidator implements PaymentMethodValidatorInterface
{
    protected const CHECKOUT_PAYMENT_METHOD_NOT_FOUND = 'checkout.payment_method.not_found';

    /**
     * @var \Spryker\Zed\Payment\Business\Method\PaymentMethodReaderInterface
     */
    protected $paymentMethodReader;

    /**
     * @param \Spryker\Zed\Payment\Business\Method\PaymentMethodReaderInterface $paymentMethodReader
     */
    public function __construct(PaymentMethodReaderInterface $paymentMethodReader)
    {
        $this->paymentMethodReader = $paymentMethodReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isQuotePaymentMethodValid(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool {
        $availablePaymentMethods = $this->paymentMethodReader->getAvailableMethods($quoteTransfer);
        $availablePaymentMethodsKeys = $this->getPaymentSelections($availablePaymentMethods);
        $usedPaymentMethodsKeys = $this->getQuotePaymentMethodsKeys($quoteTransfer);

        if (array_diff($usedPaymentMethodsKeys, $availablePaymentMethodsKeys)) {
            $checkoutErrorTransfer = (new CheckoutErrorTransfer())
                ->setMessage(static::CHECKOUT_PAYMENT_METHOD_NOT_FOUND);
            $checkoutResponseTransfer->setIsSuccess(false)->addError($checkoutErrorTransfer);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    protected function getQuotePaymentMethodsKeys(QuoteTransfer $quoteTransfer): array
    {
        $paymentMethodsKeys = [];
        if ($quoteTransfer->getPayment()) {
            $paymentMethodsKeys[] = $quoteTransfer->getPayment()->getPaymentSelection();
        }

        foreach ($quoteTransfer->getPayments() as $payment) {
            $paymentMethodsKeys[] = $payment->getPaymentSelection();
        }

        return $paymentMethodsKeys;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $availablePaymentMethods
     *
     * @return string[]
     */
    protected function getPaymentSelections(PaymentMethodsTransfer $availablePaymentMethods): array
    {
        $paymentMethods = $availablePaymentMethods->getMethods();

        $paymentSelections = [];
        foreach ($paymentMethods as $paymentMethod) {
            $paymentSelections[] = $paymentMethod->getMethodName();
        }

        return $paymentSelections;
    }
}
