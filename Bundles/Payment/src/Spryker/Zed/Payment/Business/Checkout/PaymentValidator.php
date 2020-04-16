<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Payment\PaymentConfig;

class PaymentValidator implements PaymentPluginValidatorInterface
{
    /**
     * @var \Spryker\Zed\Payment\PaymentConfig
     */
    protected $paymentConfig;

    /**
     * @param \Spryker\Zed\Payment\PaymentConfig $paymentConfig
     */
    public function __construct(PaymentConfig $paymentConfig)
    {
        $this->paymentConfig = $paymentConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isPaymentExists(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $paymentMethodStatemachineMapping = $this->paymentConfig->getPaymentStatemachineMappings();
        $paymentMethodsKeys = $this->getQuotePaymentMethodsKeys($quoteTransfer);

        foreach ($paymentMethodsKeys as $paymentMethodsKey) {
            if (!array_key_exists($paymentMethodsKey, $paymentMethodStatemachineMapping)) {
                $this->addCheckoutError($checkoutResponseTransfer, 'checkout.payment.not_found');

                return false;
            }
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
}
