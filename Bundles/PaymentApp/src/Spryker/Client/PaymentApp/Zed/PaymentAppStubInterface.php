<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PaymentApp\Zed;

use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer;
use Generated\Shared\Transfer\PaymentCustomerRequestTransfer;
use Generated\Shared\Transfer\PaymentCustomerResponseTransfer;
use Generated\Shared\Transfer\PreOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;

interface PaymentAppStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer
     */
    public function processExpressCheckoutPaymentRequest(
        ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
    ): ExpressCheckoutPaymentResponseTransfer;

    /**
     * Specification:
     * - Makes a request through the KernelApp to a PSP App to get customer data.
     * - Requires `PaymentCustomerRequestTransfer.customerPaymentServiceProviderData` to be set.
     * - Returns a `PaymentCustomerResponseTransfer.isSuccessful` with `true` if the request was successful.
     * - Returns a `PaymentCustomerResponseTransfer.customer` with customer data.
     * - Returns a `PaymentCustomerResponseTransfer.error` in case of an error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentCustomerRequestTransfer $paymentCustomerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCustomerResponseTransfer
     */
    public function getCustomer(PaymentCustomerRequestTransfer $paymentCustomerRequestTransfer): PaymentCustomerResponseTransfer;

    /**
     * Specification:
     * - Makes a request through the KernelApp to a PSP App to initialize the preOrderPayment.
     * - Requires `PreOrderPaymentRequestTransfer.quote` to be set.
     * - Requires `PreOrderPaymentRequestTransfer.payment` to be set.
     * - Returns a `PreOrderPaymentResponseTransfer.isSuccessful` with `true` if the request was successful.
     * - Returns a `PreOrderPaymentResponseTransfer.preOrderPaymentData` with PSP specific data.
     * - Returns a `PaymentCustomerResponseTransfer.error` in case of an error.
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
     * - Makes a request through the KernelApp to a PSP App to cancel a preOrderPayment.
     * - Requires `PreOrderPaymentRequestTransfer.quote` to be set.
     * - Requires `PreOrderPaymentRequestTransfer.payment` to be set.
     * - Returns a `PreOrderPaymentResponseTransfer.isSuccessful` with `true` if the request was successful.
     * - Returns a `PreOrderPaymentResponseTransfer.preOrderPaymentData` with PSP specific data.
     * - Returns a `PaymentCustomerResponseTransfer.error` in case of an error.
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
