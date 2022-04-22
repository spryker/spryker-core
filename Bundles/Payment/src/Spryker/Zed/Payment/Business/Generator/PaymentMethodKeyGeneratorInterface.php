<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Generator;

interface PaymentMethodKeyGeneratorInterface
{
    /**
     * @param string $paymentProviderName
     * @param string $paymentMethodName
     * @param string $storeName
     *
     * @return string
     */
    public function generatePaymentMethodKey(
        string $paymentProviderName,
        string $paymentMethodName,
        string $storeName
    ): string;
}
