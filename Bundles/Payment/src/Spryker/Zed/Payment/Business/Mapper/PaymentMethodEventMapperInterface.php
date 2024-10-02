<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Mapper;

use Generated\Shared\Transfer\AddPaymentMethodTransfer;
use Generated\Shared\Transfer\DeletePaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\UpdatePaymentMethodTransfer;

interface PaymentMethodEventMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\AddPaymentMethodTransfer $addPaymentMethodTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function mapAddPaymentMethodTransferToPaymentMethodTransfer(
        AddPaymentMethodTransfer $addPaymentMethodTransfer,
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodTransfer;

    /**
     * @param \Generated\Shared\Transfer\UpdatePaymentMethodTransfer $updatePaymentMethodTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function mapUpdatePaymentMethodTransferToPaymentMethodTransfer(
        UpdatePaymentMethodTransfer $updatePaymentMethodTransfer,
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodTransfer;

    /**
     * @param \Generated\Shared\Transfer\DeletePaymentMethodTransfer $deletePaymentMethodTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function mapDeletePaymentMethodTransferToPaymentMethodTransfer(
        DeletePaymentMethodTransfer $deletePaymentMethodTransfer,
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodTransfer;
}
