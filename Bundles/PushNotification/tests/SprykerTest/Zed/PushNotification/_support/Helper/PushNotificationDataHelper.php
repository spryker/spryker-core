<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PushNotification\Helper;

use ArrayObject;
use Codeception\Module;
use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;
use Orm\Zed\PushNotification\Persistence\SpyPushNotification;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationGroupQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationProviderQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;

class PushNotificationDataHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;
    use BusinessHelperTrait;

    /**
     * @param array<string, mixed> $pushNotificationProviderOverride
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderTransfer
     */
    public function havePushNotificationProvider(
        array $pushNotificationProviderOverride = []
    ): PushNotificationProviderTransfer {
        $pushNotificationProviderName = $pushNotificationProviderOverride[PushNotificationProviderTransfer::NAME] ?? uniqid();

        $pushNotificationProviderEntity = SpyPushNotificationProviderQuery::create()
            ->filterByName($pushNotificationProviderName)
            ->findOneOrCreate();
        $pushNotificationProviderEntity->save();

        $pushNotificationProviderTransfer = (new PushNotificationProviderTransfer())
            ->fromArray($pushNotificationProviderEntity->toArray(), true);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($pushNotificationProviderTransfer): void {
            $this->cleanPushNotificationProvider($pushNotificationProviderTransfer->getIdPushNotificationProviderOrFail());
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
        $pushNotificationGroupEntity = SpyPushNotificationGroupQuery::create()
            ->filterByName($pushNotificationGroupName)
            ->findOneOrCreate();
        $pushNotificationGroupEntity->save();

        $pushNotificationGroupTransfer = (new PushNotificationGroupTransfer())
            ->fromArray($pushNotificationGroupEntity->toArray(), true);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($pushNotificationGroupTransfer): void {
            $this->cleanPushNotificationGroup($pushNotificationGroupTransfer->getIdPushNotificationGroupOrFail());
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

        /** @var \Spryker\Zed\PushNotification\Business\PushNotificationFacadeInterface $pushNotificationFacade */
        $pushNotificationFacade = $this->getLocator()->pushNotification()->facade();
        $pushNotificationSubscriptionCollectionResponseTransfer = $pushNotificationFacade->createPushNotificationSubscriptionCollection(
            (new PushNotificationSubscriptionCollectionRequestTransfer())
                ->setPushNotificationSubscriptions(new ArrayObject([$pushNotificationSubscriptionTransfer]))
                ->setIsTransactional(false),
        );
        /** @var \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer */
        $pushNotificationSubscriptionTransfer = $pushNotificationSubscriptionCollectionResponseTransfer
            ->getPushNotificationSubscriptions()
            ->offsetGet(0);
        $this->getDataCleanupHelper()->_addCleanup(function () use ($pushNotificationSubscriptionTransfer): void {
            $this->cleanPushNotificationSubscription(
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
            $this->cleanPushNotification($pushNotificationTransfer->getIdPushNotificationOrFail());
        });

        return $pushNotificationTransfer;
    }

    /**
     * @param int $idPushNotificationProvider
     *
     * @return void
     */
    protected function cleanPushNotificationProvider(int $idPushNotificationProvider): void
    {
        SpyPushNotificationProviderQuery::create()
            ->filterByIdPushNotificationProvider($idPushNotificationProvider)
            ->delete();
    }

    /**
     * @param int $idPushNotificationGroup
     *
     * @return void
     */
    protected function cleanPushNotificationGroup(int $idPushNotificationGroup): void
    {
        SpyPushNotificationGroupQuery::create()
            ->filterByIdPushNotificationGroup($idPushNotificationGroup)
            ->delete();
    }

    /**
     * @param int $idPushNotificationSubscription
     *
     * @return void
     */
    protected function cleanPushNotificationSubscription(int $idPushNotificationSubscription): void
    {
        SpyPushNotificationSubscriptionQuery::create()
            ->filterByIdPushNotificationSubscription($idPushNotificationSubscription)
            ->delete();
    }

    /**
     * @param int $idPushNotification
     *
     * @return void
     */
    protected function cleanPushNotification(int $idPushNotification): void
    {
        SpyPushNotificationQuery::create()
            ->filterByIdPushNotification($idPushNotification)
            ->delete();
    }
}
