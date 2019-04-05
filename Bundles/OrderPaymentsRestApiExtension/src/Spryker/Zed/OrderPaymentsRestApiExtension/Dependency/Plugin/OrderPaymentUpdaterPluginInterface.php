<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderPaymentsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer;

interface OrderPaymentUpdaterPluginInterface
{
    /**
     * Specification:
     * - Checks whether this plugin is applicable for the payment updating.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer $updateOrderPaymentRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(UpdateOrderPaymentRequestTransfer $updateOrderPaymentRequestTransfer): bool;

    /**
     * Specification:
     * - Executes an update of order payment information.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer $updateOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer
     */
    public function updateOrderPayment(
        UpdateOrderPaymentRequestTransfer $updateOrderPaymentRequestTransfer
    ): UpdateOrderPaymentResponseTransfer;
}
