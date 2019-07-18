<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Nopayment;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class NopaymentConfig extends AbstractBundleConfig
{
    public const PAYMENT_PROVIDER_NAME = 'Nopayment';
    public const PAYMENT_METHOD_NAME = 'paid';
}
