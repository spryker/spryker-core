<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationExtension\Dependency\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;

/**
 * Plugins are triggered before a push notification is created in order to validate request parameters.
 */
interface PushNotificationValidatorPluginInterface
{
    /**
     * Specification:
     * - Validates push notifications.
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(
        ArrayObject $pushNotificationTransfers
    ): ErrorCollectionTransfer;
}
