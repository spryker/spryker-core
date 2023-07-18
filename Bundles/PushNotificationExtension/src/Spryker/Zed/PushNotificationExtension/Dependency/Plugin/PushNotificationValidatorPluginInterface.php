<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionTransfer;

/**
 * Plugins are triggered before a push notification is created in order to validate request parameters.
 */
interface PushNotificationValidatorPluginInterface
{
    /**
     * Specification:
     * - Validates a collection of push notifications.
     * - Returns a collection of validation errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationCollectionTransfer $pushNotificationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(
        PushNotificationCollectionTransfer $pushNotificationCollectionTransfer
    ): ErrorCollectionTransfer;
}
