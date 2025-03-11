<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentApp\Business\Writer;

use Generated\Shared\Transfer\PaymentAppStatusUpdatedTransfer;
use Spryker\Zed\PaymentApp\Persistence\PaymentAppEntityManagerInterface;

class PaymentAppPaymentStatusWriter implements PaymentAppPaymentStatusWriterInterface
{
    /**
     * @param \Spryker\Zed\PaymentApp\Persistence\PaymentAppEntityManagerInterface $paymentAppEntityManager
     */
    public function __construct(protected PaymentAppEntityManagerInterface $paymentAppEntityManager)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentAppStatusUpdatedTransfer $paymentAppStatusUpdatedTransfer
     *
     * @return void
     */
    public function persistPaymentAppPaymentStatus(PaymentAppStatusUpdatedTransfer $paymentAppStatusUpdatedTransfer): void
    {
        $this->paymentAppEntityManager->persistPaymentAppPaymentStatus($paymentAppStatusUpdatedTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentAppStatusUpdatedTransfer $paymentAppStatusUpdatedTransfer
     *
     * @return void
     */
    public function persistPaymentAppPaymentStatusHistory(PaymentAppStatusUpdatedTransfer $paymentAppStatusUpdatedTransfer): void
    {
        $this->paymentAppEntityManager->persistPaymentAppPaymentStatusHistory($paymentAppStatusUpdatedTransfer);
    }
}
