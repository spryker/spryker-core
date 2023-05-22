<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PaymentsRestApi;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class PaymentsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\Payment\PaymentConfig::PAYMENT_FOREIGN_PROVIDER
     *
     * @var string
     */
    public const PAYMENT_FOREIGN_PROVIDER = 'foreignPayments';
}
