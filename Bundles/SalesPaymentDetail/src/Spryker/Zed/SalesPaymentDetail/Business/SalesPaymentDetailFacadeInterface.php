<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentDetail\Business;

use Generated\Shared\Transfer\PaymentCreatedTransfer;
use Generated\Shared\Transfer\PaymentUpdatedTransfer;

interface SalesPaymentDetailFacadeInterface
{
    /**
     * Specification:
     * - Handles payment created message.
     * - When this message is received it creates a new sales payment detail entity.
     * - Requires `PaymentCreatedTransfer::PAYMENT_REFERENCE` to be set.
     * - Optional `PaymentCreatedTransfer::ENTITY_REFERENCE`. Will not be present when a payment is created before order placement (preOrder payment).
     * - When the entity for this payment reference already exists, the message will be ignored.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentCreatedTransfer $paymentCreatedTransfer
     *
     * @return void
     */
    public function handlePaymentCreated(PaymentCreatedTransfer $paymentCreatedTransfer): void;

    /**
     * Specification:
     * - Handles payment updated message.
     * - When this message is received it updates the sales payment detail entity.
     * - Requires `PaymentUpdatedTransfer::PAYMENT_REFERENCE` to be set.
     * - Requires `PaymentUpdatedTransfer::ENTITY_REFERENCE` to be set.
     * - When the entity for this payment reference does not exist, the message will be ignored.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentUpdatedTransfer $paymentUpdatedTransfer
     *
     * @return void
     */
    public function handlePaymentUpdated(PaymentUpdatedTransfer $paymentUpdatedTransfer): void;
}
