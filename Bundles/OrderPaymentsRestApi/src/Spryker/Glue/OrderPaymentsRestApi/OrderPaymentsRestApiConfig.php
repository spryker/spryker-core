<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderPaymentsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class OrderPaymentsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_ORDER_PAYMENTS = 'order-payments';

    /**
     * @var string
     */
    public const CONTROLLER_ORDER_PAYMENTS = 'order-payments-resource';

    /**
     * @var string
     */
    public const RESPONSE_CODE_ORDER_PAYMENT_IS_NOT_UPDATED = '2401';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_ORDER_PAYMENT_IS_NOT_UPDATED = 'Order payment is not updated.';
}
