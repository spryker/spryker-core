<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Persistence;

use Generated\Shared\Transfer\PaymentTransmissionResponseTransfer;

interface SalesPaymentMerchantEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer
     *
     * @return void
     */
    public function saveSalesPaymentMerchantPayout(PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer
     *
     * @return void
     */
    public function saveSalesPaymentMerchantPayoutReversal(PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer): void;
}
