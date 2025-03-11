<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\PaymentApp\Business\Status;

use Generated\Shared\Transfer\PaymentAppPaymentStatusRequestTransfer;
use Generated\Shared\Transfer\PaymentAppPaymentStatusResponseTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface PaymentAppPaymentStatusInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $paymentAppMessageTransfer
     *
     * @return void
     */
    public function savePaymentAppPaymentStatus(AbstractTransfer $paymentAppMessageTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\PaymentAppPaymentStatusRequestTransfer $paymentAppPaymentStatusRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAppPaymentStatusResponseTransfer
     */
    public function hasPaymentAppExpectedPaymentStatus(
        PaymentAppPaymentStatusRequestTransfer $paymentAppPaymentStatusRequestTransfer
    ): PaymentAppPaymentStatusResponseTransfer;
}
