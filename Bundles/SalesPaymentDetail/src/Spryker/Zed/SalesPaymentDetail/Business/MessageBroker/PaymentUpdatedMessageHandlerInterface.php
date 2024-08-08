<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentDetail\Business\MessageBroker;

use Generated\Shared\Transfer\PaymentUpdatedTransfer;

interface PaymentUpdatedMessageHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentUpdatedTransfer $paymentUpdatedTransfer
     *
     * @return void
     */
    public function handlePaymentUpdated(PaymentUpdatedTransfer $paymentUpdatedTransfer): void;
}
