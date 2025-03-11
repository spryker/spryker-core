<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentApp;

use Spryker\Shared\PaymentApp\Status\PaymentStatus;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class PaymentAppConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const PAYMENT_SERVICE_PROVIDER_ENDPOINT_NAME_CUSTOMER = 'customer';

    /**
     * @var array<string, array<string>>
     */
    public const STATUS_MAP = [
        // Covers all cases where an order was authorized before the status was reached
        PaymentStatus::STATUS_AUTHORIZED => [
            PaymentStatus::STATUS_AUTHORIZED,
            PaymentStatus::STATUS_CAPTURED,
            PaymentStatus::STATUS_CAPTURE_FAILED,
            PaymentStatus::STATUS_UNDERPAID,
            PaymentStatus::STATUS_OVERPAID,
        ],
        // Covers all cases where an order was captured before the status was reached
        PaymentStatus::STATUS_CAPTURED => [
            PaymentStatus::STATUS_CAPTURED,
            PaymentStatus::STATUS_OVERPAID,
        ],
    ];
}
