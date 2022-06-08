<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Payment\Extractor;

use Generated\Shared\Transfer\PaymentTransfer;

class PaymentMethodKeyExtractor implements PaymentMethodKeyExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    public function getPaymentSelectionKey(PaymentTransfer $paymentTransfer): string
    {
        if (!$paymentTransfer->getPaymentSelection()) {
            return '';
        }

        preg_match('/^([\w]+)/', $paymentTransfer->getPaymentSelection(), $matches);

        if (isset($matches[0])) {
            return $matches[0];
        }

        return $paymentTransfer->getPaymentSelectionOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    public function getPaymentMethodKey(PaymentTransfer $paymentTransfer): string
    {
        if (!$paymentTransfer->getPaymentSelection()) {
            return '';
        }

        preg_match('/\[([a-zA-Z0-9_-]+)\]/', $paymentTransfer->getPaymentSelection(), $matches);

        if (isset($matches[1])) {
            return $matches[1];
        }

        return $paymentTransfer->getPaymentSelectionOrFail();
    }
}
