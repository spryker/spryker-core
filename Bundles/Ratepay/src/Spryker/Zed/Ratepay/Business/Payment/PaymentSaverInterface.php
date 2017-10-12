<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Payment;

use Generated\Shared\Transfer\RatepayResponseTransfer;

interface PaymentSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\RatepayResponseTransfer $ratepayPaymentResponseTransfer
     * @param int $orderId
     *
     * @return void
     */
    public function updatePaymentMethodByPaymentResponse(RatepayResponseTransfer $ratepayPaymentResponseTransfer, $orderId);
}
