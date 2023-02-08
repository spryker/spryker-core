<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PaymentTransfer;

/**
 * Implement this interface to build a payment map key when saving order payments.
 */
interface PaymentMapKeyBuilderStrategyPluginInterface
{
    /**
     * Specification:
     * - Returns true if strategy can be used for a payment map key building.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return bool
     */
    public function isApplicable(PaymentTransfer $paymentTransfer): bool;

    /**
     * Specification:
     * - Returns a payment map key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    public function buildPaymentMapKey(PaymentTransfer $paymentTransfer): string;
}
