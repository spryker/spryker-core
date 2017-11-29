<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Nopayment\Business\Nopayment;

use ArrayObject;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Nopayment\NopaymentConfig;

class NopaymentMethodFilter
{
    /**
     * @var \Spryker\Zed\Nopayment\NopaymentConfig
     */
    protected $nopaymentConfig;

    /**
     * @param \Spryker\Zed\Nopayment\NopaymentConfig $nopaymentConfig
     */
    public function __construct(NopaymentConfig $nopaymentConfig)
    {
        $this->nopaymentConfig = $nopaymentConfig;
    }

    /**
     * @param PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer, QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getTotals()->getPriceToPay() === 0) {
            return $this->disallowRegularPaymentMethods($paymentMethodsTransfer);
        }

        return $this->disallowNoPaymentMethods($paymentMethodsTransfer);
    }

    /**
     * @param PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return PaymentMethodsTransfer
     */
    protected function disallowRegularPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer)
    {
        $allowedMethods = new ArrayObject();

        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethodTransfer) {
            if (in_array($paymentMethodTransfer->getMethodName(), $this->nopaymentConfig->getWhitelistMethods())) {
                $allowedMethods[] = $paymentMethodTransfer;
            }

            if (in_array($paymentMethodTransfer->getMethodName(), $this->nopaymentConfig->getNopaymentMethods())) {
                $allowedMethods[] = $paymentMethodTransfer;
            }
        }

        return $paymentMethodsTransfer->setMethods($allowedMethods);
    }

    /**
     * @param PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return PaymentMethodsTransfer
     */
    protected function disallowNoPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer)
    {
        $allowedMethods = new ArrayObject();

        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethodTransfer) {
            if (in_array($paymentMethodTransfer->getMethodName(), $this->nopaymentConfig->getWhitelistMethods())) {
                $allowedMethods[] = $paymentMethodTransfer;
            }

            if (!in_array($paymentMethodTransfer->getMethodName(), $this->nopaymentConfig->getNopaymentMethods())) {
                $allowedMethods[] = $paymentMethodTransfer;
            }
        }

        return $paymentMethodsTransfer->setMethods($allowedMethods);
    }
}
