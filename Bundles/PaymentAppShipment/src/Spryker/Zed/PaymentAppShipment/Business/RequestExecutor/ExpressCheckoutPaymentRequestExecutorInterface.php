<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentAppShipment\Business\RequestExecutor;

use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer;

interface ExpressCheckoutPaymentRequestExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer $expressCheckoutPaymentResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer
     */
    public function processExpressCheckoutPaymentRequest(
        ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer,
        ExpressCheckoutPaymentResponseTransfer $expressCheckoutPaymentResponseTransfer
    ): ExpressCheckoutPaymentResponseTransfer;
}
