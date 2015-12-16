<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Payolution\Session;

use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;

interface PayolutionSessionInterface
{

    /**
     * @param PayolutionCalculationResponseTransfer $payolutionCalculationResponseTransfer
     *
     * @return self
     */
    public function setInstallmentPayments(PayolutionCalculationResponseTransfer $payolutionCalculationResponseTransfer);

    /**
     * @return bool
     */
    public function hasInstallmentPayments();

    /**
     * @return PayolutionCalculationResponseTransfer
     */
    public function getInstallmentPayments();

    /**
     * @return mixed
     */
    public function removeInstallmentPayments();

}
