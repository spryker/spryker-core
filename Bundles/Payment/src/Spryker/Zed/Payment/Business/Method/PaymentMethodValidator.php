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
use Spryker\Service\Payment\PaymentServiceInterface;

class PaymentMethodValidator implements PaymentMethodValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CHECKOUT_PAYMENT_METHOD_INVALID = 'checkout.payment_method.invalid';

    /**
     * @var \Spryker\Zed\Payment\Business\Method\PaymentMethodReaderInterface
     */
    protected $paymentMethodReader;

    /**
     * @var \Spryker\Service\Payment\PaymentServiceInterface
     */
    protected $paymentService;

    /**
     * @param \Spryker\Zed\Payment\Business\Method\PaymentMethodReaderInterface $paymentMethodReader
     * @param \Spryker\Service\Payment\PaymentServiceInterface $paymentService
     */
    public function __construct(
        PaymentMethodReaderInterface $paymentMethodReader,
        PaymentServiceInterface $paymentService
    ) {
        $this->paymentMethodReader = $paymentMethodReader;
        $this->paymentService = $paymentService;
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

        if (!array_diff($usedPaymentMethodsKeys, $availablePaymentMethodsKeys)) {
            return true;
        }

        $checkoutErrorTransfer = (new CheckoutErrorTransfer())
            ->setMessage(static::GLOSSARY_KEY_CHECKOUT_PAYMENT_METHOD_INVALID);
        $checkoutResponseTransfer
            ->setIsSuccess(false)
            ->addError($checkoutErrorTransfer);

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string>
     */
    protected function getQuotePaymentMethodsKeys(QuoteTransfer $quoteTransfer): array
    {
        $paymentMethodsKeys = [];
        if ($quoteTransfer->getPayment()) {
            $paymentMethodsKeys[] = $this->paymentService->getPaymentMethodKey($quoteTransfer->getPayment());
        }

        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            $paymentMethodsKeys[] = $this->paymentService->getPaymentMethodKey($paymentTransfer);
        }

        return $paymentMethodsKeys;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $availablePaymentMethods
     *
     * @return array<string>
     */
    protected function getPaymentSelections(PaymentMethodsTransfer $availablePaymentMethods): array
    {
        $paymentMethods = $availablePaymentMethods->getMethods();

        $paymentSelections = [];
        foreach ($paymentMethods as $paymentMethod) {
            $paymentSelections[] = $paymentMethod->getPaymentMethodKey();
        }

        return $paymentSelections;
    }
}
