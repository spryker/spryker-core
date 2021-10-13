<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\AvailabilityNotification;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class AvailabilityNotificationConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const MESSAGE_PRODUCT_NOT_FOUND = 'Product not found.';

    /**
     * @var string
     */
    public const MESSAGE_SUBSCRIPTION_ALREADY_EXISTS = 'Subscription already exists.';

    /**
     * @var string
     */
    public const MESSAGE_SUBSCRIPTION_DOES_NOT_EXIST = "Subscription doesn't exist.";
}
