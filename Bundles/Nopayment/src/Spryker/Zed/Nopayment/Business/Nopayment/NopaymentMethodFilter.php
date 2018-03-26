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
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer, QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getTotals()->getPriceToPay() === 0) {
            return $this->disallowRegularPaymentMethods($paymentMethodsTransfer);
        }

        return $this->disallowNoPaymentMethods($paymentMethodsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
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
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
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
