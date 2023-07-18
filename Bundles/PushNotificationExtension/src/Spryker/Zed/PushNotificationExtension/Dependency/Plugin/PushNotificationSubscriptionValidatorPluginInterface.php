<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer;

/**
 * Plugins are triggered before a push notification subscription is created in order to validate request parameters.
 */
interface PushNotificationSubscriptionValidatorPluginInterface
{
    /**
     * Specification:
     * - Validates a collection of push notification subscriptions.
     * - Returns a collection of validation errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer $pushNotificationSubscriptionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(
        PushNotificationSubscriptionCollectionTransfer $pushNotificationSubscriptionCollectionTransfer
    ): ErrorCollectionTransfer;
}
