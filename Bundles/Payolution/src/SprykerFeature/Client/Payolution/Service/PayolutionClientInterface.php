<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Payolution\Service;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;

interface PayolutionClientInterface
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return PayolutionCalculationResponseTransfer
     */
    public function calculateInstallmentPayments(CheckoutRequestTransfer $checkoutRequestTransfer);

}
