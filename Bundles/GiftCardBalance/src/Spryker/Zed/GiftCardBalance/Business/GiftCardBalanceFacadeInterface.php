<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\PaymentTransfer;

interface GiftCardBalanceFacadeInterface
{
    /**
     * Specification:
     * - Recaps the gift card history
     * - Substitutes used amounts from the provided gift card value
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return int
     */
    public function getRemainingValue(GiftCardTransfer $giftCardTransfer);

    /**
     * Specification:
     * - Recaps the gift card history
     * - Sum used amounts and compares to the gift card value
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return bool
     */
    public function hasPositiveBalance(GiftCardTransfer $giftCardTransfer);

    /**
     * Specification:
     * - Takes a payment amount and creates a gift card balance log record
     * - Throws an exception if provided payment transfer does not contain a gift card
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveTransactionLog(PaymentTransfer $paymentTransfer, CheckoutResponseTransfer $checkoutResponse);
}
