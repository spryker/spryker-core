<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentApp\Business;

use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer;
use Generated\Shared\Transfer\PaymentCustomerRequestTransfer;
use Generated\Shared\Transfer\PaymentCustomerResponseTransfer;

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
}
