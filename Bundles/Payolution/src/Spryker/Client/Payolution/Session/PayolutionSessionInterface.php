<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Payolution\Session;

use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;

interface PayolutionSessionInterface
{

    /**
     * @param \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer $payolutionCalculationResponseTransfer
     *
     * @return self
     */
    public function setInstallmentPayments(PayolutionCalculationResponseTransfer $payolutionCalculationResponseTransfer);

    /**
     * @return bool
     */
    public function hasInstallmentPayments();

    /**
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    public function getInstallmentPayments();

    /**
     * @return mixed
     */
    public function removeInstallmentPayments();

}
