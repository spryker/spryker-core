<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Mapper;

use Generated\Shared\Transfer\AddPaymentMethodTransfer;
use Generated\Shared\Transfer\DeletePaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;

class PaymentMethodEventMapper implements PaymentMethodEventMapperInterface
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
    ): PaymentMethodTransfer {
        $paymentMethodTransfer
            ->setLabelName($addPaymentMethodTransfer->getName())
            ->setGroupName($addPaymentMethodTransfer->getProviderName())
            ->setPaymentAuthorizationEndpoint($addPaymentMethodTransfer->getPaymentAuthorizationEndpoint());

        return $paymentMethodTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DeletePaymentMethodTransfer $deletePaymentMethodTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function mapDeletePaymentMethodTransferToPaymentMethodTransfer(
        DeletePaymentMethodTransfer $deletePaymentMethodTransfer,
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodTransfer {
        $paymentMethodTransfer
            ->setLabelName($deletePaymentMethodTransfer->getName())
            ->setGroupName($deletePaymentMethodTransfer->getProviderName());

        return $paymentMethodTransfer;
    }
}
