<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentApp\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer;
use Generated\Shared\Transfer\PaymentCustomerRequestTransfer;
use Generated\Shared\Transfer\PaymentCustomerResponseTransfer;
use Generated\Shared\Transfer\PreOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PaymentAppFacadeInterface
{
    /**
     * Specification:
     * - Processes express checkout payment request.
     * - Requires `ExpressCheckoutPaymentRequest.quote.payment` to be set.
     * - Requires `ExpressCheckoutPaymentRequest.quote.store` to be set.
     * - Expands `ExpressCheckoutPaymentRequest.quote.payment` with payment selection.
     * - Executes stack of {@link \Spryker\Zed\PaymentAppExtension\Dependency\Plugin\ExpressCheckoutPaymentRequestProcessorPluginInterface} plugins.
     * - Reloads all items in quote and recalculates quote totals {@uses \Spryker\Zed\Cart\Business\CartFacade::reloadItemsInQuote()}.
     * - Returns `ExpressCheckoutPaymentResponse.quote` with updated payment information if all plugins were executed successfully.
     * - Returns `ExpressCheckoutPaymentResponse` populated with `errors` if any plugin execution failed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer
     */
    public function processExpressCheckoutPaymentRequest(
        ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
    ): ExpressCheckoutPaymentResponseTransfer;

    /**
     * Specification:
     * - Gets customer data from the PSP App
     * - Requires `PaymentCustomerResponseTransfer.customer` to be set.
     * - Returns `PaymentCustomerResponseTransfer.isSuccess` true if the cancellation was successful.
     * - Returns `PaymentCustomerResponseTransfer.customer` with customer data.
     * - Returns `PaymentCustomerResponseTransfer.error` with error message if the request failed.
     * - Returns `PaymentCustomerResponseTransfer.isSuccess` false if the request failed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentCustomerRequestTransfer $paymentCustomerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCustomerResponseTransfer
     */
    public function getCustomer(
        PaymentCustomerRequestTransfer $paymentCustomerRequestTransfer
    ): PaymentCustomerResponseTransfer;

    /**
     * Specification:
     * - Initializes a pre-order payment (pre-order is before the order gets persisted).
     * - Requires `PreOrderPaymentRequestTransfer.quote` to be set.
     * - Requires `PreOrderPaymentRequestTransfer.quote.store` to be set.
     * - Requires `PreOrderPaymentRequestTransfer.paymentMethod` to be set.
     * - Returns `PreOrderPaymentResponseTransfer.isSuccess` true if the payment was initialized.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer
     */
    public function initializePreOrderPayment(
        PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
    ): PreOrderPaymentResponseTransfer;

    /**
     * Specification:
     * - Confirms a pre-order payment after the order was persisted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function confirmPreOrderPayment(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void;

    /**
     * Specification:
     * - Cancels a pre-order payment (pre-order is before the order gets persisted).
     * - Requires `PreOrderPaymentResponseTransfer.paymentMethod` to be set.
     * - Returns `PreOrderPaymentResponseTransfer.isSuccess` true if the cancellation was successful.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer
     */
    public function cancelPreOrderPayment(
        PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
    ): PreOrderPaymentResponseTransfer;
}
