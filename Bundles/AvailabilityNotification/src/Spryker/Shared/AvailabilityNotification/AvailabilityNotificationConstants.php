<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\AvailabilityNotification;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface AvailabilityNotificationConstants
{
    /**
     * Specification:
     * - Defines a custom URI where you can unsubscribe from availability notification by subscription key on the Yves side.
     * - A string placeholder for subscriptionKey can be inserted in sprintf format
     * - A string placeholder for locale can be inserted in sprintf format
     *
     * @api
     */
    public const AVAILABILITY_NOTIFICATION_UNSUBSCRIBE_BY_KEY_URI = 'AVAILABILITY_NOTIFICATION_UNSUBSCRIBE_BY_KEY_URI';
}
