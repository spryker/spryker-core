<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentDetail\Business;

use Generated\Shared\Transfer\PaymentCreatedTransfer;

interface SalesPaymentDetailFacadeInterface
{
    /**
     * Specification:
     * - Handles payment created message.
     * - When this message is received it creates a new sales payment detail entity.
     * - Requires `PaymentCreatedTransfer::PAYMENT_REFERENCE` to be set.
     * - Requires `PaymentCreatedTransfer::ENTITY_REFERENCE` to be set.
     * - When the entity for this payment reference already exists, the message will be ignored.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentCreatedTransfer $paymentCreatedTransfer
     *
     * @return void
     */
    public function handlePaymentCreated(PaymentCreatedTransfer $paymentCreatedTransfer): void;
}
