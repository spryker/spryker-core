<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer;

/**
 * Plugin interface is used to expand the `PaymentAuthorizeRequestTransfer` with additional data.
 *
 * Executes on payment authorization request.
 */
interface PaymentAuthorizeRequestExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands payment authorize request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer
     */
    public function expand(
        PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
    ): PaymentAuthorizeRequestTransfer;
}
