<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business\MessageEmitter;

use Generated\Shared\Transfer\EventPaymentTransfer;

interface MessageEmitterInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendCancelPaymentMessage(EventPaymentTransfer $eventPaymentTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendCapturePaymentMessage(EventPaymentTransfer $eventPaymentTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendRefundPaymentMessage(EventPaymentTransfer $eventPaymentTransfer): void;
}
