<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;

class PushNotificationSubscriptionDeliveryLogExpander implements PushNotificationSubscriptionDeliveryLogExpanderInterface
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
    ): PushNotificationTransfer {
        $pushNotificationSubscriptionTransfersIndexedByIdPushNotificationSubscription = $this->getPushNotificationSubscriptionTransfersIndexedByIdPushNotificationSubscription(
            $pushNotificationTransfer->getSubscriptions(),
        );
        $idPushNotificationSubscription = $pushNotificationSubscriptionDeliveryLogTransfer
            ->getPushNotificationSubscriptionOrFail()
            ->getIdPushNotificationSubscriptionOrFail();
        $pushNotificationSubscriptionTransfer = $pushNotificationSubscriptionTransfersIndexedByIdPushNotificationSubscription[$idPushNotificationSubscription];
        $pushNotificationSubscriptionTransfer->addDeliveryLog($pushNotificationSubscriptionDeliveryLogTransfer);

        return $pushNotificationTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>
     */
    protected function getPushNotificationSubscriptionTransfersIndexedByIdPushNotificationSubscription(
        ArrayObject $pushNotificationSubscriptionTransfers
    ): array {
        $pushNotificationSubscriptionTransfersIndexedByIdPushNotificationSubscription = [];
        foreach ($pushNotificationSubscriptionTransfers as $pushNotificationSubscriptionTransfer) {
            $idPushNotificationSubscription = $pushNotificationSubscriptionTransfer->getIdPushNotificationSubscriptionOrFail();
            $pushNotificationSubscriptionTransfersIndexedByIdPushNotificationSubscription[$idPushNotificationSubscription] = $pushNotificationSubscriptionTransfer;
        }

        return $pushNotificationSubscriptionTransfersIndexedByIdPushNotificationSubscription;
    }
}
