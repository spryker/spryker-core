<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\PaymentApp\Persistence;

use Generated\Shared\Transfer\PaymentAppStatusUpdatedTransfer;

interface PaymentAppEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentAppStatusUpdatedTransfer $paymentAppStatusUpdatedTransfer
     *
     * @return void
     */
    public function persistPaymentAppPaymentStatus(PaymentAppStatusUpdatedTransfer $paymentAppStatusUpdatedTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\PaymentAppStatusUpdatedTransfer $paymentAppStatusUpdatedTransfer
     *
     * @return void
     */
    public function persistPaymentAppPaymentStatusHistory(PaymentAppStatusUpdatedTransfer $paymentAppStatusUpdatedTransfer): void;
}
