<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Business\Resolver;

use Generated\Shared\Transfer\PaymentTransfer;

interface PaymentMethodKeyResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    public function resolvePaymentMethodKey(PaymentTransfer $paymentTransfer): string;
}
