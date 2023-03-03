<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Extractor;

use ArrayObject;

interface PushNotificationSubscriptionDeliveryLogExtractorInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer>
     */
    public function extractDeliveryLogs(ArrayObject $pushNotificationTransfers): ArrayObject;
}
