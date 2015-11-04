<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\Handler\Calculation;

use Generated\Shared\Payolution\CheckoutRequestInterface;
use Generated\Shared\Payolution\PayolutionResponseInterface;

interface CalculationInterface
{
    /**
     * @param CheckoutRequestInterface $checkoutRequestTransfer
     *
     * @return PayolutionResponseInterface
     */
    public function calculateInstallmentPayments(CheckoutRequestInterface $checkoutRequestTransfer);

}
