<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Payolution\Zed;

use Generated\Shared\Transfer\PayolutionCalculationRequestTransfer;
use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;

interface PayolutionStubInterface
{

    /**
     * @param PayolutionCalculationRequestTransfer $calculationRequestTransfer
     *
     * @return PayolutionCalculationResponseTransfer
     */
    public function calculateInstallmentPayments(PayolutionCalculationRequestTransfer $calculationRequestTransfer);

}
