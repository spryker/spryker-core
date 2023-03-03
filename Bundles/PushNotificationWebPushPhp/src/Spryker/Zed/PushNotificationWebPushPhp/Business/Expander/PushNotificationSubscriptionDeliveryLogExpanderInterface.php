<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business\Expander;

use Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;

interface PushNotificationSubscriptionDeliveryLogExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationTransfer $pushNotificationTransfer
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer $pushNotificationSubscriptionDeliveryLogTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationTransfer
     */
    public function extendPushNotificationPushNotificationSubscriptionDeliveryLogs(
        PushNotificationTransfer $pushNotificationTransfer,
        PushNotificationSubscriptionDeliveryLogTransfer $pushNotificationSubscriptionDeliveryLogTransfer
    ): PushNotificationTransfer;
}
