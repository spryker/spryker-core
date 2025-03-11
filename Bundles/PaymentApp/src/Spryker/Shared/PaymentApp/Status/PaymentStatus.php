<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Shared\PaymentApp\Status;

/**
 * This ENUM defines an interface between Payment Apps and their status of the payment. When this ENUM is changed it also needs to be changed on the App side.
 *
 * @see https://github.com/spryker/app-payment/blob/master/src/Spryker/Zed/AppPayment/Business/Payment/Status/PaymentStatus.php
 */
enum PaymentStatus
{
    /**
     * @var string
     */
    public const STATUS_NEW = 'new';

    /**
     * @var string
     */
    public const STATUS_CANCELED = 'canceled';

    /**
     * @var string
     */
    public const STATUS_CANCELLATION_FAILED = 'cancellation_failed';

    /**
     * @var string
     */
    public const STATUS_CAPTURED = 'captured';

    /**
     * @var string
     */
    public const STATUS_CAPTURE_FAILED = 'capture_failed';

    /**
     * @var string
     */
    public const STATUS_CAPTURE_REQUESTED = 'capture_requested';

    /**
     * @var string
     */
    public const STATUS_AUTHORIZED = 'authorized';

    /**
     * @var string
     */
    public const STATUS_AUTHORIZATION_FAILED = 'authorization_failed';

    /**
     * @var string
     */
    public const STATUS_OVERPAID = 'overpaid';

    /**
     * @var string
     */
    public const STATUS_UNDERPAID = 'underpaid';
}
