<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PushNotificationWebPushPhp;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface PushNotificationWebPushPhpConstants
{
    /**
     * Specification:
     * - Provides VAPID public key.
     * - Used for authentication to send push notifications.
     *
     * @api
     *
     * @var string
     */
    public const VAPID_PUBLIC_KEY = 'PUSH_NOTIFICATION_WEB_PUSH_PHP:VAPID_PUBLIC_KEY';

    /**
     * Specification:
     * - Provides VAPID private key.
     * - Used for authentication to send push notifications.
     *
     * @api
     *
     * @var string
     */
    public const VAPID_PRIVATE_KEY = 'PUSH_NOTIFICATION_WEB_PUSH_PHP:VAPID_PRIVATE_KEY';

    /**
     * Specification:
     * - Provides VAPID subject.
     * - Used for authentication to send push notifications.
     *
     * @api
     *
     * @var string
     */
    public const VAPID_SUBJECT = 'PUSH_NOTIFICATION_WEB_PUSH_PHP:VAPID_SUBJECT';
}
