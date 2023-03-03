<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Creator;

use ArrayObject;

interface PushNotificationSubscriptionDeliveryLogCreatorInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer> $pushNotificationSubscriptionDeliveryLogTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer>
     */
    public function createPushNotificationSubscriptionDeliveryLogCollection(
        ArrayObject $pushNotificationSubscriptionDeliveryLogTransfers
    ): ArrayObject;
}
