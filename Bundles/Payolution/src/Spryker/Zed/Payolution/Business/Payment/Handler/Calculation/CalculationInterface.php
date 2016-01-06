<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Business\Payment\Handler\Calculation;

use Generated\Shared\Transfer\PayolutionCalculationRequestTransfer;
use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;

interface CalculationInterface
{

    /**
     * @param PayolutionCalculationRequestTransfer $calculationRequestTransfer
     *
     * @return PayolutionCalculationResponseTransfer
     */
    public function calculateInstallmentPayments(PayolutionCalculationRequestTransfer $calculationRequestTransfer);

}
