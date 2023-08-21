<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payment;

use Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer;
use Generated\Shared\Transfer\PaymentAuthorizeResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PaymentClientInterface
{
    /**
     * Specification:
     * - Makes a request from given PaymentAuthorizeRequestTransfer.
     * - Adds `Authorization` header to request using `PaymentAuthorizeRequest.authorizaton` if exists.
     * - Adds `X-Store-Reference` header to request using `PaymentAuthorizeRequest.storeReference` if exists.
     * - Adds `X-Tenant-Identifier` header to request using `PaymentAuthorizeRequest.tenantIdentifier` if exists.
     * - Sends a request to a foreign payment service.
     * - Returns a PaymentAuthorizeResponseTransfer with the received data.
     *
     * @api
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
     * - Requests available payment methods from Zed
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer);
}
