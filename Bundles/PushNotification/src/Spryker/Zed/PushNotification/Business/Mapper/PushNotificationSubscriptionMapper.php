<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Mapper;

use DateTime;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer;

class PushNotificationSubscriptionMapper implements PushNotificationSubscriptionMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionDeleteCriteriaTransfer $pushNotificationSubscriptionCollectionDeleteCriteriaTransfer
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer $pushNotificationSubscriptionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer
     */
    public function mapPushNotificationSubscriptionCollectionDeleteCriteriaTransferToPushNotificationSubscriptionCriteriaTransfer(
        PushNotificationSubscriptionCollectionDeleteCriteriaTransfer $pushNotificationSubscriptionCollectionDeleteCriteriaTransfer,
        PushNotificationSubscriptionCriteriaTransfer $pushNotificationSubscriptionCriteriaTransfer
    ): PushNotificationSubscriptionCriteriaTransfer {
        if (!$pushNotificationSubscriptionCollectionDeleteCriteriaTransfer->getIsExpired()) {
            return $pushNotificationSubscriptionCriteriaTransfer;
        }

        if (!$pushNotificationSubscriptionCriteriaTransfer->getPushNotificationSubscriptionConditions()) {
            $pushNotificationSubscriptionCriteriaTransfer = $pushNotificationSubscriptionCriteriaTransfer->setPushNotificationSubscriptionConditions(
                new PushNotificationSubscriptionConditionsTransfer(),
            );
        }

        $pushNotificationSubscriptionCriteriaTransfer->getPushNotificationSubscriptionConditionsOrFail()
            ->setExpiredAt(
                (string)(new DateTime())->getTimestamp(),
            );

        return $pushNotificationSubscriptionCriteriaTransfer;
    }
}
