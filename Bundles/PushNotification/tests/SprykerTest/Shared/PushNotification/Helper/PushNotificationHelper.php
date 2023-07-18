<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\PushNotification\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\PushNotificationProviderBuilder;
use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;
use Orm\Zed\PushNotification\Persistence\SpyPushNotification;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationGroupQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationProviderQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionDeliveryLog;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionDeliveryLogQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;

class PushNotificationHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;
    use BusinessHelperTrait;

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderTransfer
     */
    public function havePushNotificationProvider(
        array $seed = []
    ): PushNotificationProviderTransfer {
        $pushNotificationProviderTransfer = (new PushNotificationProviderBuilder($seed))->build();

        $pushNotificationProviderEntity = $this->getPushNotificationProviderQuery()
            ->filterByName($pushNotificationProviderTransfer->getNameOrFail())
            ->findOneOrCreate();

        $pushNotificationProviderEntity->save();

        $pushNotificationProviderTransfer = (new PushNotificationProviderTransfer())
            ->fromArray($pushNotificationProviderEntity->toArray(), true);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($pushNotificationProviderTransfer): void {
            $this->deletePushNotificationProvider($pushNotificationProviderTransfer->getIdPushNotificationProviderOrFail());
        });

        return $pushNotificationProviderTransfer;
    }

    /**
     * @param array<string, mixed> $pushNotificationGroupOverride
     *
     * @return \Generated\Shared\Transfer\PushNotificationGroupTransfer
     */
    public function havePushNotificationGroup(array $pushNotificationGroupOverride = []): PushNotificationGroupTransfer
    {
        $pushNotificationGroupName = $pushNotificationGroupOverride[PushNotificationGroupTransfer::NAME] ?? uniqid();
        $pushNotificationGroupEntity = $this->getPushNotificationGroupQuery()
            ->filterByName($pushNotificationGroupName)
            ->findOneOrCreate();
        $pushNotificationGroupEntity->save();

        $pushNotificationGroupTransfer = (new PushNotificationGroupTransfer())
            ->fromArray($pushNotificationGroupEntity->toArray(), true);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($pushNotificationGroupTransfer): void {
            $this->deletePushNotificationGroup($pushNotificationGroupTransfer->getIdPushNotificationGroupOrFail());
        });

        return $pushNotificationGroupTransfer;
    }

    /**
     * @param array<string, mixed> $pushNotificationSubscriptionOverride
     * @param array<string, mixed> $pushNotificationProviderOverride
     * @param array<string, mixed> $pushNotificationGroupOverride
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function havePushNotificationSubscription(
        array $pushNotificationSubscriptionOverride = [],
        array $pushNotificationProviderOverride = [],
        array $pushNotificationGroupOverride = []
    ): PushNotificationSubscriptionTransfer {
        $pushNotificationProviderTransfer = $this->havePushNotificationProvider($pushNotificationProviderOverride);
        $pushNotificationGroupTransfer = $this->havePushNotificationGroup($pushNotificationGroupOverride);

        $pushNotificationSubscriptionTransfer = (new PushNotificationSubscriptionTransfer())
            ->fromArray($pushNotificationSubscriptionOverride)
            ->setProvider($pushNotificationProviderTransfer)
            ->setGroup($pushNotificationGroupTransfer);

        $pushNotificationSubscriptionCollectionRequestTransfer = (new PushNotificationSubscriptionCollectionRequestTransfer())
            ->addPushNotificationSubscription($pushNotificationSubscriptionTransfer)
            ->setIsTransactional(false);

        $pushNotificationSubscriptionCollectionResponseTransfer = $this->getLocator()
            ->pushNotification()
            ->facade()
            ->createPushNotificationSubscriptionCollection($pushNotificationSubscriptionCollectionRequestTransfer);

        /** @var \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer */
        $pushNotificationSubscriptionTransfer = $pushNotificationSubscriptionCollectionResponseTransfer
            ->getPushNotificationSubscriptions()
            ->getIterator()
            ->current();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($pushNotificationSubscriptionTransfer): void {
            $this->deletePushNotificationSubscription(
                $pushNotificationSubscriptionTransfer->getIdPushNotificationSubscriptionOrFail(),
            );
        });

        return $pushNotificationSubscriptionTransfer;
    }

    /**
     * @param array<string, mixed> $pushNotificationOverride
     * @param array<string, mixed> $pushNotificationProviderOverride
     * @param array<string, mixed> $pushNotificationGroupOverride
     *
     * @return \Generated\Shared\Transfer\PushNotificationTransfer
     */
    public function havePushNotification(
        array $pushNotificationOverride = [],
        array $pushNotificationProviderOverride = [],
        array $pushNotificationGroupOverride = []
    ): PushNotificationTransfer {
        $pushNotificationPayload = $pushNotificationOverride[PushNotificationTransfer::PAYLOAD] ?? [];

        $pushNotificationProviderTransfer = $this->havePushNotificationProvider($pushNotificationProviderOverride);
        $pushNotificationGroupTransfer = $this->havePushNotificationGroup($pushNotificationGroupOverride);

        $pushNotificationEntity = (new SpyPushNotification())
            ->setFkPushNotificationGroup($pushNotificationGroupTransfer->getIdPushNotificationGroupOrFail())
            ->setFkPushNotificationProvider($pushNotificationProviderTransfer->getIdPushNotificationProviderOrFail())
            ->setPayload(json_encode($pushNotificationPayload));
        $pushNotificationEntity->save();

        $pushNotificationTransfer = (new PushNotificationTransfer())
            ->fromArray($pushNotificationEntity->toArray(), true)
            ->setPayload($pushNotificationPayload)
            ->setGroup($pushNotificationGroupTransfer)
            ->setProvider($pushNotificationProviderTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($pushNotificationTransfer): void {
            $this->deletePushNotification($pushNotificationTransfer->getIdPushNotificationOrFail());
        });

        return $pushNotificationTransfer;
    }

    /**
     * @param array<string, mixed> $pushNotificationSubscriptionDeliveryLogOverride
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer
     */
    public function havePushNotificationSubscriptionDeliveryLog(
        array $pushNotificationSubscriptionDeliveryLogOverride = []
    ): PushNotificationSubscriptionDeliveryLogTransfer {
        $pushNotificationOverride = $pushNotificationSubscriptionDeliveryLogOverride[PushNotificationSubscriptionDeliveryLogTransfer::PUSH_NOTIFICATION] ?? [];
        $pushNotificationTransfer = (new PushNotificationTransfer())->fromArray($pushNotificationOverride);
        if (!$pushNotificationTransfer->getIdPushNotification()) {
            $pushNotificationTransfer = $this->havePushNotification($pushNotificationOverride);
        }

        $pushNotificationSubscriptionOverride = $pushNotificationSubscriptionDeliveryLogOverride[PushNotificationSubscriptionDeliveryLogTransfer::PUSH_NOTIFICATION_SUBSCRIPTION] ?? [];
        $pushNotificationSubscriptionTransfer = (new PushNotificationSubscriptionTransfer())->fromArray($pushNotificationSubscriptionOverride);
        if (!$pushNotificationSubscriptionTransfer->getIdPushNotificationSubscription()) {
            $pushNotificationSubscriptionTransfer = $this->havePushNotificationSubscription($pushNotificationSubscriptionOverride);
        }

        $pushNotificationSubscriptionDeliveryLogEntity = (new SpyPushNotificationSubscriptionDeliveryLog())
            ->setFkPushNotification($pushNotificationTransfer->getIdPushNotificationOrFail())
            ->setFkPushNotificationSubscription($pushNotificationSubscriptionTransfer->getIdPushNotificationSubscriptionOrFail());
        $pushNotificationSubscriptionDeliveryLogEntity->save();

        $pushNotificationSubscriptionDeliveryLogTransfer = (new PushNotificationSubscriptionDeliveryLogTransfer())
            ->setIdPushNotificationSubscriptionDeliveryLog($pushNotificationSubscriptionDeliveryLogEntity->getIdPushNotificationSubscriptionDeliveryLog())
            ->setPushNotification($pushNotificationTransfer)
            ->setPushNotificationSubscription($pushNotificationSubscriptionTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($pushNotificationSubscriptionDeliveryLogTransfer): void {
            $this->deletePushNotificationSubscriptionDeliveryLog($pushNotificationSubscriptionDeliveryLogTransfer->getIdPushNotificationSubscriptionDeliveryLogOrFail());
        });

        return $pushNotificationSubscriptionDeliveryLogTransfer;
    }

    /**
     * @param int $idPushNotificationGroup
     *
     * @return void
     */
    protected function deletePushNotificationGroup(int $idPushNotificationGroup): void
    {
        $pushNotificationGroup = $this->getPushNotificationGroupQuery()->findOneByIdPushNotificationGroup($idPushNotificationGroup);

        if ($pushNotificationGroup) {
            $pushNotificationGroup->delete();
        }
    }

    /**
     * @param int $idPushNotificationSubscription
     *
     * @return void
     */
    protected function deletePushNotificationSubscription(int $idPushNotificationSubscription): void
    {
        $pushNotificationSubscriptionEntity = $this->getPushNotificationSubscriptionQuery()
            ->findOneByIdPushNotificationSubscription($idPushNotificationSubscription);

        if ($pushNotificationSubscriptionEntity) {
            $pushNotificationSubscriptionEntity->delete();
        }
    }

    /**
     * @param int $idPushNotification
     *
     * @return void
     */
    protected function deletePushNotification(int $idPushNotification): void
    {
        $pushNotificationEntity = $this->getPushNotificationQuery()->findOneByIdPushNotification($idPushNotification);

        if ($pushNotificationEntity) {
            $pushNotificationEntity->delete();
        }
    }

    /**
     * @param int $idPushNotificationProvider
     *
     * @return void
     */
    protected function deletePushNotificationProvider(int $idPushNotificationProvider): void
    {
        $pushNotificationProviderEntity = $this->getPushNotificationProviderQuery()
            ->findOneByIdPushNotificationProvider($idPushNotificationProvider);

        if ($pushNotificationProviderEntity) {
            $pushNotificationProviderEntity->delete();
        }
    }

    /**
     * @param int $idPushNotificationSubscriptionDeliveryLog
     *
     * @return void
     */
    protected function deletePushNotificationSubscriptionDeliveryLog(int $idPushNotificationSubscriptionDeliveryLog): void
    {
        $pushNotificationSubscriptionDeliveryLogEntity = $this->getPushNotificationSubscriptionDeliveryLogQuery()
            ->findByIdPushNotificationSubscriptionDeliveryLog($idPushNotificationSubscriptionDeliveryLog);

        if ($pushNotificationSubscriptionDeliveryLogEntity) {
            $pushNotificationSubscriptionDeliveryLogEntity->delete();
        }
    }

    /**
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationGroupQuery
     */
    protected function getPushNotificationGroupQuery(): SpyPushNotificationGroupQuery
    {
        return SpyPushNotificationGroupQuery::create();
    }

    /**
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionQuery
     */
    protected function getPushNotificationSubscriptionQuery(): SpyPushNotificationSubscriptionQuery
    {
        return SpyPushNotificationSubscriptionQuery::create();
    }

    /**
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationQuery
     */
    protected function getPushNotificationQuery(): SpyPushNotificationQuery
    {
        return SpyPushNotificationQuery::create();
    }

    /**
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationProviderQuery
     */
    protected function getPushNotificationProviderQuery(): SpyPushNotificationProviderQuery
    {
        return SpyPushNotificationProviderQuery::create();
    }

    /**
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionDeliveryLogQuery
     */
    protected function getPushNotificationSubscriptionDeliveryLogQuery(): SpyPushNotificationSubscriptionDeliveryLogQuery
    {
        return SpyPushNotificationSubscriptionDeliveryLogQuery::create();
    }
}
