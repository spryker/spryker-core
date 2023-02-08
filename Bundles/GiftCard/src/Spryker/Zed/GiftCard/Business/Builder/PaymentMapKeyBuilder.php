<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Builder;

use Generated\Shared\Transfer\PaymentTransfer;

class PaymentMapKeyBuilder implements PaymentMapKeyBuilderInterface
{
    /**
     * @var string
     */
    protected const PAYMENT_MAP_KEY_PATTERN = '%s-%s-%s';

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    public function buildMapKey(PaymentTransfer $paymentTransfer): string
    {
        return sprintf(
            static::PAYMENT_MAP_KEY_PATTERN,
            $paymentTransfer->getPaymentProviderOrFail(),
            $paymentTransfer->getPaymentMethodOrFail(),
            $paymentTransfer->getGiftCardOrFail()->getIdGiftCardOrFail(),
        );
    }
}
