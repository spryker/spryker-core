<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Nopayment\Business;

use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

interface NopaymentFacadeInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     *
     * @return array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem>
     */
    public function setAsPaid(array $orderItems);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function isPaid(SpySalesOrderItem $orderItem);

    /**
     * Specification:
     * - Filters Nopayment payment methods
     * - Whitelisted payment methods always will be included (@see \Spryker\Shared\Nopayment\NopaymentConstants::WHITELIST_PAYMENT_METHODS)
     * - If quote total > 0, then filter Nopayment payment methods out
     * - If quote total = 0, then filter regular payment methods out
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer, QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Returns true if there is no Nopayment payment provider in QuoteTransfer.payments otherwise does additional checks/logic.
     * - Returns true if QuoteTransfer.totals.priceToPay greater than 0 otherwise adds an error into CheckoutResponseTransfer and returns false.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkOrderPreSaveConditions(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool;

    /**
     * Specification:
     * - Checks if `QuoteTransfer.totals.priceToPay` equals zero.
     * - Updates `QuoteTransfer.payment` to `PaymentTransfer` with no payment if condition is met.
     * - Returns `CartCodeRequestTransfer.isSuccessful` with `true`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function updateCartCodeQuotePayment(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer;
}
