<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Persistence\Mapper;

use Generated\Shared\Transfer\PushNotificationCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;
use Orm\Zed\PushNotification\Persistence\Base\SpyPushNotificationSubscription;
use Orm\Zed\PushNotification\Persistence\SpyPushNotification;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilEncodingServiceInterface;

class PushNotificationMapper
{
    /**
     * @var \Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilEncodingServiceInterface
     */
    protected PushNotificationToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @var \Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationGroupMapper
     */
    protected PushNotificationGroupMapper $pushNotificationGroupMapper;

    /**
     * @var \Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationProviderMapper
     */
    protected PushNotificationProviderMapper $pushNotificationProviderMapper;

    /**
     * @param \Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationGroupMapper $pushNotificationGroupMapper
     * @param \Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationProviderMapper $pushNotificationProviderMapper
     */
    public function __construct(
        PushNotificationToUtilEncodingServiceInterface $utilEncodingService,
        PushNotificationGroupMapper $pushNotificationGroupMapper,
        PushNotificationProviderMapper $pushNotificationProviderMapper
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->pushNotificationGroupMapper = $pushNotificationGroupMapper;
        $this->pushNotificationProviderMapper = $pushNotificationProviderMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationTransfer $pushNotificationTransfer
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotification $pushNotificationEntity
     *
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotification
     */
    public function mapPushNotificationTransferToPushNotificationEntity(
        PushNotificationTransfer $pushNotificationTransfer,
        SpyPushNotification $pushNotificationEntity
    ): SpyPushNotification {
        $pushNotificationData = $pushNotificationTransfer->toArray();
        if (isset($pushNotificationData[PushNotificationTransfer::PAYLOAD])) {
            unset($pushNotificationData[PushNotificationTransfer::PAYLOAD]);
        }
        $pushNotificationEntity->fromArray($pushNotificationData);

        $pushNotificationEntity->setPayload(
            $this->utilEncodingService->encodeJson($pushNotificationTransfer->getPayload()),
        );
        $pushNotificationEntity->setFkPushNotificationGroup(
            $pushNotificationTransfer->getGroupOrFail()->getIdPushNotificationGroupOrFail(),
        );
        $pushNotificationEntity->setFkPushNotificationProvider(
            $pushNotificationTransfer->getProviderOrFail()->getIdPushNotificationProviderOrFail(),
        );

        return $pushNotificationEntity;
    }

    /**
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotification $pushNotificationEntity
     * @param \Generated\Shared\Transfer\PushNotificationTransfer $pushNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationTransfer
     */
    public function mapPushNotificationEntityToPushNotificationTransfer(
        SpyPushNotification $pushNotificationEntity,
        PushNotificationTransfer $pushNotificationTransfer
    ): PushNotificationTransfer {
        $pushNotificationTransfer->fromArray($pushNotificationEntity->toArray(), true);

        $pushNotificationTransfer->setPayload(
            (array)$this->utilEncodingService->decodeJson((string)$pushNotificationEntity->getPayload(), true),
        );

        $pushNotificationTransfer = $this->mapPushNotificationProvider(
            $pushNotificationEntity,
            $pushNotificationTransfer,
        );

        $pushNotificationTransfer = $this->mapPushNotificationSubscriptions(
            $pushNotificationEntity,
            $pushNotificationTransfer,
        );

        return $pushNotificationTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\PushNotification\Persistence\SpyPushNotification> $pushNotificationEntityCollection
     * @param \Generated\Shared\Transfer\PushNotificationCollectionTransfer $pushNotificationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionTransfer
     */
    public function mapPushNotificationEntityCollectionToPushNotificationCollectionTransfer(
        ObjectCollection $pushNotificationEntityCollection,
        PushNotificationCollectionTransfer $pushNotificationCollectionTransfer
    ): PushNotificationCollectionTransfer {
        foreach ($pushNotificationEntityCollection as $pushNotificationEntity) {
            $pushNotificationCollectionTransfer->addPushNotification(
                $this->mapPushNotificationEntityToPushNotificationTransfer(
                    $pushNotificationEntity,
                    new PushNotificationTransfer(),
                ),
            );
        }

        return $pushNotificationCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\PushNotification\Persistence\Base\SpyPushNotificationSubscription $pushNotificationSubscriptionEntity
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function mapPushNotificationSubscriptionEntityToPushNotificationSubscriptionTransfer(
        SpyPushNotificationSubscription $pushNotificationSubscriptionEntity,
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionTransfer {
        $pushNotificationSubscriptionData = $pushNotificationSubscriptionEntity->toArray();
        $pushNotificationSubscriptionData[PushNotificationSubscriptionTransfer::PAYLOAD] = $this
            ->utilEncodingService
            ->decodeJson(
                $pushNotificationSubscriptionData[PushNotificationSubscriptionTransfer::PAYLOAD],
                true,
            );
        $pushNotificationSubscriptionTransfer->fromArray($pushNotificationSubscriptionData, true);

        return $pushNotificationSubscriptionTransfer;
    }

    /**
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotification $pushNotificationEntity
     * @param \Generated\Shared\Transfer\PushNotificationTransfer $pushNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationTransfer
     */
    protected function mapPushNotificationProvider(
        SpyPushNotification $pushNotificationEntity,
        PushNotificationTransfer $pushNotificationTransfer
    ): PushNotificationTransfer {
        $pushNotificationProviderTransfer = $this
            ->pushNotificationProviderMapper
            ->mapPushNotificationProviderEntityToPushNotificationProviderTransfer(
                $pushNotificationEntity->getSpyPushNotificationProvider(),
                new PushNotificationProviderTransfer(),
            );

        return $pushNotificationTransfer->setProvider($pushNotificationProviderTransfer);
    }

    /**
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotification $pushNotificationEntity
     * @param \Generated\Shared\Transfer\PushNotificationTransfer $pushNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationTransfer
     */
    protected function mapPushNotificationSubscriptions(
        SpyPushNotification $pushNotificationEntity,
        PushNotificationTransfer $pushNotificationTransfer
    ): PushNotificationTransfer {
        foreach ($pushNotificationEntity->getPushNotificationSubscriptions() as $subscriptionEntity) {
            $pushNotificationSubscriptionTransfer = $this->mapPushNotificationSubscriptionEntityToPushNotificationSubscriptionTransfer(
                $subscriptionEntity,
                new PushNotificationSubscriptionTransfer(),
            );

            $pushNotificationTransfer->addSubscription($pushNotificationSubscriptionTransfer);
        }

        return $pushNotificationTransfer;
    }
}
