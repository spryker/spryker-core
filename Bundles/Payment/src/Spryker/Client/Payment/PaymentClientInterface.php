<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payment;

use Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer;
use Generated\Shared\Transfer\PaymentAuthorizeResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PreOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PaymentClientInterface
{
    /**
     * Specification:
     * - Makes a request from given PaymentAuthorizeRequestTransfer.
     * - Adds `Authorization` header to request using `PaymentAuthorizeRequest.authorization` if exists.
     * - Adds `X-Store-Reference` header to request using `PaymentAuthorizeRequest.storeReference` if exists.
     * - Adds `X-Tenant-Identifier` header to request using `PaymentAuthorizeRequest.tenantIdentifier` if exists.
     * - Sends a request to a foreign payment service.
     * - Returns a PaymentAuthorizeResponseTransfer with the received data.
     *
     * @api
     *
     * @deprecated The \Spryker\Zed\Payment\Business\ForeignPayment\ForeignPaymentInterface::initializePayment makes direct use of the KernelApp::makeRequest() method.
     *
     * @param \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAuthorizeResponseTransfer
     */
    public function authorizeForeignPayment(
        PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
    ): PaymentAuthorizeResponseTransfer;

    /**
     * Specification:
     * - Makes a request through the KernelApp to a PSP App to initialize a pre-order payment.
     * - Requires `PreOrderPaymentRequestTransfer::QUOTE` to be set.
     * - Requires `PreOrderPaymentRequestTransfer::PAYMENT_METHOD` to be set.
     * - Returns a `PreOrderPaymentResponseTransfer::IS_SUCCESS` with `true` if the request was successful.
     * - Returns a `PreOrderPaymentResponseTransfer::PRE_ORDER_PAYMENT_DATA` with specific to the PSP related data (e.g. to initialize a PSP SDK).
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
     * - Makes a request through the KernelApp to a PSP App to cancel a pre-order payment.
     * - Requires `PreOrderPaymentRequestTransfer::PAYMENT_METHOD` to be set.
     * - Requires `PreOrderPaymentRequestTransfer::PRE_ORDER_PAYMENT_DATA` to be set.
     * - Returns a `PreOrderPaymentResponseTransfer::IS_SUCCESS` with `true` if the request was successful.
     * - Returns a `PreOrderPaymentResponseTransfer::ERROR` in case of an error.
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

    /**
     * Specification:
     * - Requests available payment methods from Zed
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer): PaymentMethodsTransfer;
}
