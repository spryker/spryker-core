<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payment\Zed;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PreOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PaymentStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer
     */
    public function initializePreOrderPayment(
        PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
    ): PreOrderPaymentResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer
     */
    public function cancelPreOrderPayment(
        PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
    ): PreOrderPaymentResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer): PaymentMethodsTransfer;
}
