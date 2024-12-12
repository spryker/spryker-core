<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PaymentApp;

use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer;
use Generated\Shared\Transfer\PaymentCustomerRequestTransfer;
use Generated\Shared\Transfer\PaymentCustomerResponseTransfer;
use Generated\Shared\Transfer\PreOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;

interface PaymentAppClientInterface
{
    /**
     * Specification:
     * - Processes express checkout payment request.
     * - Makes Zed request.
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
     * - Request payment customer information via Zed from a PSP App.
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
     * - Makes Zed request.
     * - Makes a request through the KernelApp to a PSP App to initialize a pre-order payment.
     * - Requires `PreOrderPaymentRequestTransfer.quote` to be set.
     * - Requires `PreOrderPaymentRequestTransfer.paymentMethod` to be set.
     * - Returns a `PreOrderPaymentResponseTransfer.isSuccess` with `true` if the request was successful.
     * - Returns a `PreOrderPaymentResponseTransfer.preOrderPaymentData` with specific to the PSP related data (e.g. to initialize a PSP SDK).
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
     * - Makes Zed request.
     * - Makes a request through the KernelApp to a PSP App to cancel a pre-order payment.
     * - Requires `PreOrderPaymentRequestTransfer.paymentMethod` to be set.
     * - Requires `PreOrderPaymentRequestTransfer.preOrderPaymentData` to be set.
     * - Returns a `PreOrderPaymentResponseTransfer.isSuccess` with `true` if the request was successful.
     * - Returns a `PreOrderPaymentResponseTransfer.error` in case of an error.
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
