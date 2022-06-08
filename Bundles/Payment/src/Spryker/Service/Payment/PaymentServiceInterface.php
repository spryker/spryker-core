<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Payment;

use Generated\Shared\Transfer\PaymentTransfer;

interface PaymentServiceInterface
{
    /**
     * Specification:
     * - Uses `Payment.paymentSelection`.
     * - Returns empty string if `PaymentTransfer.paymentSelection` is `null`.
     * - Returns only the first matching string for the pattern `[a-zA-Z0-9_]+`.
     * - Returns the unchanged value if there is no match.
     *
     * @api
     *
     * @example 'foreignPayments[paymentKey]' becomes 'foreignPayments'
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    public function getPaymentSelectionKey(PaymentTransfer $paymentTransfer): string;

    /**
     * Specification:
     * - Uses `Payment.paymentSelection`.
     * - Returns empty string if `PaymentTransfer.paymentSelection` is `null`.
     * - Returns only the first matching string for the provided pattern in square brackets.
     * - Returns the specified value if there is no match.
     *
     * @api
     *
     * @example 'foreignPayments[paymentKey]' becomes 'paymentKey'
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    public function getPaymentMethodKey(PaymentTransfer $paymentTransfer): string;
}
