<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;

interface OrderPaymentExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands PaymentTransfer before setting it to an OrderTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    public function expand(OrderTransfer $orderTransfer, PaymentTransfer $paymentTransfer): PaymentTransfer;
}
