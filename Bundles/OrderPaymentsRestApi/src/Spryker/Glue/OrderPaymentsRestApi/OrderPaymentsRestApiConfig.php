<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderPaymentsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class OrderPaymentsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_ORDER_PAYMENTS = 'order-payments';

    public const CONTROLLER_ORDER_PAYMENTS = 'order-payments-resource';

    public const ACTION_ORDER_PAYMENTS_POST = 'post';

    public const RESOURCE_ORDER_PAYMENTS_IS_PROTECTED = true;
}
