<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Extractor;

use ArrayObject;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;

class PushNotificationSubscriptionDeliveryLogExtractor implements PushNotificationSubscriptionDeliveryLogExtractorInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer>
     */
    public function extractDeliveryLogs(ArrayObject $pushNotificationTransfers): ArrayObject
    {
        $pushNotificationSubscriptionDeliveryLogTransfers = new ArrayObject();
        /** @var \Generated\Shared\Transfer\PushNotificationTransfer $pushNotificationTransfer */
        foreach ($pushNotificationTransfers as $pushNotificationTransfer) {
            $pushNotificationSubscriptionDeliveryLogTransfers = $this
                ->extendDeliveryLogCollectionByPushNotificationDeliveryLogs(
                    $pushNotificationTransfer,
                    $pushNotificationSubscriptionDeliveryLogTransfers,
                );
        }

        return $pushNotificationSubscriptionDeliveryLogTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationTransfer $pushNotificationTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer> $pushNotificationSubscriptionDeliveryLogTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer>
     */
    protected function extendDeliveryLogCollectionByPushNotificationDeliveryLogs(
        PushNotificationTransfer $pushNotificationTransfer,
        ArrayObject $pushNotificationSubscriptionDeliveryLogTransfers
    ): ArrayObject {
        foreach ($pushNotificationTransfer->getSubscriptions() as $pushNotificationSubscriptionTransfer) {
            $pushNotificationSubscriptionDeliveryLogTransfers = $this
                ->extendDeliveryLogCollectionByPushNotificationSubscriptionDeliveryLogs(
                    $pushNotificationSubscriptionTransfer,
                    $pushNotificationSubscriptionDeliveryLogTransfers,
                );
        }

        return $pushNotificationSubscriptionDeliveryLogTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer> $pushNotificationSubscriptionDeliveryLogTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer>
     */
    protected function extendDeliveryLogCollectionByPushNotificationSubscriptionDeliveryLogs(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer,
        ArrayObject $pushNotificationSubscriptionDeliveryLogTransfers
    ): ArrayObject {
        foreach ($pushNotificationSubscriptionTransfer->getDeliveryLogs() as $pushNotificationSubscriptionDeliveryLog) {
            $pushNotificationSubscriptionDeliveryLogTransfers->append($pushNotificationSubscriptionDeliveryLog);
        }

        return $pushNotificationSubscriptionDeliveryLogTransfers;
    }
}
