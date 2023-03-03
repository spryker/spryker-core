<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Generator;

use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;

interface PushNotificationSubscriptionCheckSumGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return string
     */
    public function generatePayloadChecksum(PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer): string;
}
