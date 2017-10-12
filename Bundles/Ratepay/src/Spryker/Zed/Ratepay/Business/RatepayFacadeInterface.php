<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentInitTransfer;
use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;
use Generated\Shared\Transfer\RatepayResponseTransfer;

/**
 * @method \Spryker\Zed\Ratepay\Business\RatepayBusinessFactory getFactory()
 */
interface RatepayFacadeInterface
{
    /**
     * Specification:
     * - Saves order payment method data according to quote and checkout response transfer data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);

    /**
     * Specification:
     * - Performs init payment request to RatePAY Getaway to retrieve transaction data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RatepayPaymentInitTransfer $ratepayPaymentInitTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function initPayment(RatepayPaymentInitTransfer $ratepayPaymentInitTransfer);

    /**
     * Specification:
     * - Performs the init payment request to RatePAY Gateway to retrieve transaction data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function requestPayment(RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer);

    /**
     * Specification:
     * - Updates paymentMethod in dataBase according response from paymentRequest
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RatepayResponseTransfer $ratepayPaymentResponseTransfer
     * @param int $orderId
     *
     * @return void
     */
    public function updatePaymentMethodByPaymentResponse(RatepayResponseTransfer $ratepayPaymentResponseTransfer, $orderId);

    /**
     * Specification:
     * - Performs the payment confirmation request to RatePAY Gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function confirmPayment(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Performs the delivery confirmation request to RatePAY Gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $partialOrderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function confirmDelivery(
        OrderTransfer $orderTransfer,
        OrderTransfer $partialOrderTransfer,
        array $orderItems
    );

    /**
     * Specification:
     * - Performs the cancel payment request to RatePAY Gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $partialOrderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function cancelPayment(
        OrderTransfer $orderTransfer,
        OrderTransfer $partialOrderTransfer,
        array $orderItems
    );

    /**
     * Specification:
     * - Performs refund payment request to RatePAY Gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $partialOrderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function refundPayment(
        OrderTransfer $orderTransfer,
        OrderTransfer $partialOrderTransfer,
        array $orderItems
    );

    /**
     * Specification:
     * - Performs the installment payment method calculator configuration request to RatePAY Gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer
     */
    public function installmentConfiguration(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Performs the installment payment method calculator calculation request to RatePAY Gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer
     */
    public function installmentCalculation(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Checks if the payment request success
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPaymentRequestSuccess(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Checks if the payment confirmation API request got success response from RatePAY Gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPaymentConfirmed(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Checks if the delivery confirmation API request got success response from RatePAY Gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isDeliveryConfirmed(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Checks if the payment refund API request got success response from RatePAY Gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Checks if the payment cancellation API request got success response from RatePAY Gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCancellationConfirmed(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Expands cart items with necessary for RatePAY information (short_description, long_description, etc).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $change);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function postSaveHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);

    /**
     * Specification:
     * - Installs bundle translations to project glossary.
     *
     * @api
     *
     * @return void
     */
    public function install();

    /**
     * Specification:
     * - Retrieves profile data from RatePAY Gateway.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function requestProfile();
}
