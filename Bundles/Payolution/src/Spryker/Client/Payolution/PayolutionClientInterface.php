<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payolution;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;

interface PayolutionClientInterface
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    public function calculateInstallmentPayments(CheckoutRequestTransfer $checkoutRequestTransfer);

    /**
     * @param \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer $payolutionCalculationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    public function storeInstallmentPaymentsInSession(PayolutionCalculationResponseTransfer $payolutionCalculationResponseTransfer);

    /**
     * @return bool
     */
    public function hasInstallmentPaymentsInSession();

    /**
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    public function getInstallmentPaymentsFromSession();

    /**
     * @return mixed
     */
    public function removeInstallmentPaymentsFromSession();

}
