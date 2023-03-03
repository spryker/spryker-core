<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Sender;

use Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer;

interface PushNotificationSenderInterface
{
    /**
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    public function sendPushNotifications(): PushNotificationCollectionResponseTransfer;
}
