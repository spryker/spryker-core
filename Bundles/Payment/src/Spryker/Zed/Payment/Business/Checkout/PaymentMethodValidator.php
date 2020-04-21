<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Payment\Business\Method\PaymentMethodReaderInterface;

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
    public function isPaymentMethodExists(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $availablePaymentMethods = $this->paymentMethodReader->getAvailableMethods($quoteTransfer);
        $availablePaymentMethodsKeys = $this->getPaymentSelections($availablePaymentMethods);
        $usedPaymentMethodsKeys = $this->getQuotePaymentMethodsKeys($quoteTransfer);

        if (array_diff($usedPaymentMethodsKeys, $availablePaymentMethodsKeys)) {
            $this->addCheckoutError(
                $checkoutResponseTransfer,
                static::CHECKOUT_PAYMENT_METHOD_NOT_FOUND
            );

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
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param string $message
     *
     * @return void
     */
    protected function addCheckoutError(CheckoutResponseTransfer $checkoutResponseTransfer, string $message): void
    {
        $checkoutResponseTransfer->setIsSuccess(false)
            ->addError(
                (new CheckoutErrorTransfer())->setMessage($message)
            );
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
