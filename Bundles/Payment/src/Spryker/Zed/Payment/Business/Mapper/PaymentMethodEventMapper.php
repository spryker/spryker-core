<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Mapper;

use Generated\Shared\Transfer\PaymentMethodAddedTransfer;
use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;

class PaymentMethodEventMapper implements PaymentMethodEventMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodAddedTransfer $paymentMethodAddedTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function mapPaymentMethodAddedTransferToPaymentMethodTransfer(
        PaymentMethodAddedTransfer $paymentMethodAddedTransfer,
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodTransfer {
        $paymentMethodTransfer
            ->setLabelName($paymentMethodAddedTransfer->getName())
            ->setGroupName($paymentMethodAddedTransfer->getProviderName())
            ->setPaymentAuthorizationEndpoint($paymentMethodAddedTransfer->getPaymentAuthorizationEndpoint());

        return $paymentMethodTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function mapPaymentMethodDeletedTransferToPaymentMethodTransfer(
        PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer,
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodTransfer {
        $paymentMethodTransfer
            ->setLabelName($paymentMethodDeletedTransfer->getName())
            ->setGroupName($paymentMethodDeletedTransfer->getProviderName());

        return $paymentMethodTransfer;
    }
}
