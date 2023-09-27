<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Persistence;

use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;
use Orm\Zed\PushNotification\Persistence\SpyPushNotification;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationProvider;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscription;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionDeliveryLog;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\PushNotification\Persistence\PushNotificationPersistenceFactory getFactory()
 */
class PushNotificationEntityManager extends AbstractEntityManager implements PushNotificationEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function createPushNotificationSubscription(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionTransfer {
        $pushNotificationSubscriptionMapper = $this->getFactory()->createPushNotificationSubscriptionMapper();
        $pushNotificationSubscriptionEntity = $pushNotificationSubscriptionMapper
            ->mapPushNotificationSubscriptionTransferToPushNotificationSubscriptionEntity(
                $pushNotificationSubscriptionTransfer,
                new SpyPushNotificationSubscription(),
            );
        $pushNotificationSubscriptionEntity->save();

        return $pushNotificationSubscriptionMapper
            ->mapPushNotificationSubscriptionEntityToPushNotificationSubscriptionTransfer(
                $pushNotificationSubscriptionEntity,
                $pushNotificationSubscriptionTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationTransfer $pushNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationTransfer
     */
    public function createPushNotification(
        PushNotificationTransfer $pushNotificationTransfer
    ): PushNotificationTransfer {
        $pushNotificationEntity = $this->getFactory()
            ->createPushNotificationMapper()
            ->mapPushNotificationTransferToPushNotificationEntity(
                $pushNotificationTransfer,
                new SpyPushNotification(),
            );
        $pushNotificationEntity->save();

        return $this->getFactory()
            ->createPushNotificationMapper()
            ->mapPushNotificationEntityToPushNotificationTransfer($pushNotificationEntity, $pushNotificationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderTransfer $pushNotificationProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderTransfer
     */
    public function createPushNotificationProvider(
        PushNotificationProviderTransfer $pushNotificationProviderTransfer
    ): PushNotificationProviderTransfer {
        $pushNotificationProviderMapper = $this->getFactory()->createPushNotificationProviderMapper();

        $pushNotificationProviderEntity = $pushNotificationProviderMapper
            ->mapPushNotificationProviderTransferToPushNotificationProviderEntity(
                $pushNotificationProviderTransfer,
                new SpyPushNotificationProvider(),
            );

        $pushNotificationProviderEntity->save();

        return $pushNotificationProviderMapper
            ->mapPushNotificationProviderEntityToPushNotificationProviderTransfer(
                $pushNotificationProviderEntity,
                $pushNotificationProviderTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return void
     */
    public function deletePushNotificationSubscription(PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer): void
    {
        $this->getFactory()
            ->createPushNotificationSubscriptionDeliveryLogQuery()
            ->filterByFkPushNotificationSubscription($pushNotificationSubscriptionTransfer->getIdPushNotificationSubscriptionOrFail())
            ->delete();

        $this->getFactory()
            ->createPushNotificationSubscriptionQuery()
            ->filterByIdPushNotificationSubscription($pushNotificationSubscriptionTransfer->getIdPushNotificationSubscriptionOrFail())
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderTransfer $pushNotificationProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderTransfer
     */
    public function updatePushNotificationProvider(
        PushNotificationProviderTransfer $pushNotificationProviderTransfer
    ): PushNotificationProviderTransfer {
        $pushNotificationProviderEntity = $this->getFactory()
            ->createPushNotificationProviderQuery()
            ->filterByUuid($pushNotificationProviderTransfer->getUuidOrFail())
            ->findOne();

        if (!$pushNotificationProviderEntity) {
            return $pushNotificationProviderTransfer;
        }

        $pushNotificationProviderMapper = $this->getFactory()->createPushNotificationProviderMapper();
        $pushNotificationProviderEntity = $pushNotificationProviderMapper->mapPushNotificationProviderTransferToPushNotificationProviderEntity(
            $pushNotificationProviderTransfer,
            $pushNotificationProviderEntity,
        );

        $pushNotificationProviderEntity->save();

        return $pushNotificationProviderMapper->mapPushNotificationProviderEntityToPushNotificationProviderTransfer(
            $pushNotificationProviderEntity,
            $pushNotificationProviderTransfer,
        );
    }

    /**
     * @param list<string> $pushNotificationProviderUuids
     *
     * @return void
     */
    public function deletePushNotificationProviders(
        array $pushNotificationProviderUuids
    ): void {
        $this->getFactory()
            ->createPushNotificationProviderQuery()
            ->filterByUuid_In($pushNotificationProviderUuids)
            ->find()
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationGroupTransfer $pushNotificationGroupTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationGroupTransfer
     */
    public function createPushNotificationGroup(
        PushNotificationGroupTransfer $pushNotificationGroupTransfer
    ): PushNotificationGroupTransfer {
        $pushNotificationGroupEntity = $this->getFactory()
            ->createPushNotificationGroupQuery()
            ->filterByName($pushNotificationGroupTransfer->getNameOrFail())
            ->filterByIdentifier($pushNotificationGroupTransfer->getIdentifier())
            ->findOneOrCreate();

        if ($pushNotificationGroupEntity->isNew()) {
            $pushNotificationGroupEntity->save();
        }

        return $this->getFactory()
            ->createPushNotificationGroupMapper()
            ->mapPushNotificationGroupEntityToPushNotificationGroupTransfer(
                $pushNotificationGroupEntity,
                $pushNotificationGroupTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer $pushNotificationSubscriptionDeliveryLogTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer
     */
    public function createPushNotificationSubscriptionDeliverLog(
        PushNotificationSubscriptionDeliveryLogTransfer $pushNotificationSubscriptionDeliveryLogTransfer
    ): PushNotificationSubscriptionDeliveryLogTransfer {
        $pushNotificationSubscriptionDeliveryLogMapper = $this->getFactory()
            ->createPushNotificationSubscriptionDeliveryLogMapper();

        $pushNotificationSubscriptionDeliveryLogEntity = $pushNotificationSubscriptionDeliveryLogMapper
            ->mapPushNotificationSubscriptionDeliveryLogTransferToPushNotificationSubscriptionDeliveryLogEntity(
                $pushNotificationSubscriptionDeliveryLogTransfer,
                new SpyPushNotificationSubscriptionDeliveryLog(),
            );
        $pushNotificationSubscriptionDeliveryLogEntity->save();

        return $pushNotificationSubscriptionDeliveryLogMapper
            ->mapPushNotificationSubscriptionDeliveryLogEntityToPushNotificationSubscriptionDeliveryLogTransfer(
                $pushNotificationSubscriptionDeliveryLogEntity,
                $pushNotificationSubscriptionDeliveryLogTransfer,
            );
    }
}
