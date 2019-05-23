<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderPaymentsRestApi\Business\OrderPayment;

use Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer;

interface OrderPaymentUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer $updateOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer
     */
    public function updateOrderPayment(
        UpdateOrderPaymentRequestTransfer $updateOrderPaymentRequestTransfer
    ): UpdateOrderPaymentResponseTransfer;
}
