<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Writer;

use Generated\Shared\Transfer\PaymentMethodResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderResponseTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;

interface PaymentWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderResponseTransfer
     */
    public function createPaymentProvider(PaymentProviderTransfer $paymentProviderTransfer): PaymentProviderResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function createPaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function deactivatePaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function activatePaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodResponseTransfer;
}
