<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business\Creator;

use ArrayObject;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToWebPushInterface;

interface WebPushQueueCreatorInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToWebPushInterface
     */
    public function queuePushNotifications(ArrayObject $pushNotificationTransfers): PushNotificationWebPushPhpToWebPushInterface;
}
