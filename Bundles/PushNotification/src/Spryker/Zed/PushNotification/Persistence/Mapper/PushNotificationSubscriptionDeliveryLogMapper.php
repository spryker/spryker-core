<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Persistence\Mapper;

use Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionDeliveryLog;

class PushNotificationSubscriptionDeliveryLogMapper
{
    /**
     * @var \Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationMapper
     */
    protected PushNotificationMapper $pushNotificationMapper;

    /**
     * @var \Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationSubscriptionMapper
     */
    protected PushNotificationSubscriptionMapper $pushNotificationSubscriptionMapper;

    /**
     * @param \Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationMapper $pushNotificationMapper
     * @param \Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationSubscriptionMapper $pushNotificationSubscriptionMapper
     */
    public function __construct(PushNotificationMapper $pushNotificationMapper, PushNotificationSubscriptionMapper $pushNotificationSubscriptionMapper)
    {
        $this->pushNotificationMapper = $pushNotificationMapper;
        $this->pushNotificationSubscriptionMapper = $pushNotificationSubscriptionMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer $pushNotificationSubscriptionDeliveryLogTransfer
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionDeliveryLog $pushNotificationSubscriptionDeliveryLogEntity
     *
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionDeliveryLog
     */
    public function mapPushNotificationSubscriptionDeliveryLogTransferToPushNotificationSubscriptionDeliveryLogEntity(
        PushNotificationSubscriptionDeliveryLogTransfer $pushNotificationSubscriptionDeliveryLogTransfer,
        SpyPushNotificationSubscriptionDeliveryLog $pushNotificationSubscriptionDeliveryLogEntity
    ): SpyPushNotificationSubscriptionDeliveryLog {
        if (
            $pushNotificationSubscriptionDeliveryLogTransfer->getPushNotification()
            && $pushNotificationSubscriptionDeliveryLogTransfer->getPushNotificationOrFail()->getIdPushNotification()
        ) {
            $idPushNotification = $pushNotificationSubscriptionDeliveryLogTransfer
                ->getPushNotificationOrFail()
                ->getIdPushNotificationOrFail();
            $pushNotificationSubscriptionDeliveryLogEntity->setFkPushNotification($idPushNotification);
        }

        if (
            $pushNotificationSubscriptionDeliveryLogTransfer->getPushNotificationSubscription()
            && $pushNotificationSubscriptionDeliveryLogTransfer->getPushNotificationSubscriptionOrFail()->getIdPushNotificationSubscription()
        ) {
            $idPushNotificationSubscription = $pushNotificationSubscriptionDeliveryLogTransfer
                ->getPushNotificationSubscriptionOrFail()
                ->getIdPushNotificationSubscriptionOrFail();
            $pushNotificationSubscriptionDeliveryLogEntity->setFkPushNotificationSubscription(
                $idPushNotificationSubscription,
            );
        }

        return $pushNotificationSubscriptionDeliveryLogEntity;
    }

    /**
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionDeliveryLog $pushNotificationSubscriptionDeliveryLogEntity
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer $pushNotificationSubscriptionDeliveryLogTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer
     */
    public function mapPushNotificationSubscriptionDeliveryLogEntityToPushNotificationSubscriptionDeliveryLogTransfer(
        SpyPushNotificationSubscriptionDeliveryLog $pushNotificationSubscriptionDeliveryLogEntity,
        PushNotificationSubscriptionDeliveryLogTransfer $pushNotificationSubscriptionDeliveryLogTransfer
    ): PushNotificationSubscriptionDeliveryLogTransfer {
        $pushNotificationTransfer = $this->pushNotificationMapper->mapPushNotificationEntityToPushNotificationTransfer(
            $pushNotificationSubscriptionDeliveryLogEntity->getSpyPushNotification(),
            new PushNotificationTransfer(),
        );
        $pushNotificationSubscriptionTransfer = $this->pushNotificationSubscriptionMapper->mapPushNotificationSubscriptionEntityToPushNotificationSubscriptionTransfer(
            $pushNotificationSubscriptionDeliveryLogEntity->getSpyPushNotificationSubscription(),
            new PushNotificationSubscriptionTransfer(),
        );

        return $pushNotificationSubscriptionDeliveryLogTransfer
            ->setPushNotification($pushNotificationTransfer)
            ->setPushNotificationSubscription($pushNotificationSubscriptionTransfer);
    }
}
