<?php

/**
 * This file is part of the Spryker Platform.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\Payolution\Session;

use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;

interface PayolutionSessionInterface
{

    /**
     * @param \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer $payolutionCalculationResponseTransfer
     *
     * @return $this
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
