<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Nopayment\Business\Nopayment;

use ArrayObject;
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
     * @param \ArrayObject|\Generated\Shared\Transfer\PaymentInformationTransfer[] $paymentMethods
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PaymentInformationTransfer[]
     */
    public function filterPaymentMethods(ArrayObject $paymentMethods, QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getTotals()->getPriceToPay() === 0) {
            return $this->disallowRegularPaymentMethods($paymentMethods);
        }

        return $this->disallowNoPaymentMethods($paymentMethods);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentInformationTransfer[]|\ArrayObject $paymentMethods
     *
     * @return \Generated\Shared\Transfer\PaymentInformationTransfer[]|\ArrayObject
     */
    protected function disallowRegularPaymentMethods(ArrayObject $paymentMethods)
    {
        $allowedMethods = new ArrayObject();

        foreach ($paymentMethods as $paymentMethod) {
            if (in_array($paymentMethod->getMethod(), $this->nopaymentConfig->getWhitelistMethods())) {
                $allowedMethods[] = $paymentMethod;
            }

            if (in_array($paymentMethod->getMethod(), $this->nopaymentConfig->getNopaymentMethods())) {
                $allowedMethods[] = $paymentMethod;
            }
        }

        return $allowedMethods;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentInformationTransfer[]|\ArrayObject $paymentMethods
     *
     * @return \Generated\Shared\Transfer\PaymentInformationTransfer[]|\ArrayObject
     */
    protected function disallowNoPaymentMethods(ArrayObject $paymentMethods)
    {
        $allowedMethods = new ArrayObject();

        foreach ($paymentMethods as $paymentMethod) {
            if (in_array($paymentMethod->getMethod(), $this->nopaymentConfig->getWhitelistMethods())) {
                $allowedMethods[] = $paymentMethod;
            }

            if (!in_array($paymentMethod->getMethod(), $this->nopaymentConfig->getNopaymentMethods())) {
                $allowedMethods[] = $paymentMethod;
            }
        }

        return $allowedMethods;
    }

}
