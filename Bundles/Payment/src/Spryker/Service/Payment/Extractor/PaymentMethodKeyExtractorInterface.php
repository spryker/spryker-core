<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Payment\Extractor;

use Generated\Shared\Transfer\PaymentTransfer;

interface PaymentMethodKeyExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    public function getPaymentSelectionKey(PaymentTransfer $paymentTransfer): string;

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    public function getPaymentMethodKey(PaymentTransfer $paymentTransfer): string;
}
