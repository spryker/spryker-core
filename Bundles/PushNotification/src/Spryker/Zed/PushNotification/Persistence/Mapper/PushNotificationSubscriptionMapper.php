<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Persistence\Mapper;

use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscription;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilEncodingServiceInterface;

class PushNotificationSubscriptionMapper
{
    /**
     * @var \Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilEncodingServiceInterface
     */
    protected PushNotificationToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(PushNotificationToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscription $pushNotificationSubscriptionEntity
     *
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscription
     */
    public function mapPushNotificationSubscriptionTransferToPushNotificationSubscriptionEntity(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer,
        SpyPushNotificationSubscription $pushNotificationSubscriptionEntity
    ): SpyPushNotificationSubscription {
        $pushNotificationSubscriptionData = $pushNotificationSubscriptionTransfer->toArray();
        unset($pushNotificationSubscriptionData[PushNotificationSubscriptionTransfer::PAYLOAD]);

        $pushNotificationSubscriptionEntity->fromArray($pushNotificationSubscriptionData);

        $pushNotificationSubscriptionEntity->setFkPushNotificationGroup(
            $pushNotificationSubscriptionTransfer->getGroupOrFail()->getIdPushNotificationGroupOrFail(),
        );
        $pushNotificationSubscriptionEntity->setFkPushNotificationProvider(
            $pushNotificationSubscriptionTransfer->getProviderOrFail()->getIdPushNotificationProviderOrFail(),
        );
        $pushNotificationSubscriptionEntity->setPayload(
            $this->utilEncodingService->encodeJson($pushNotificationSubscriptionTransfer->getPayload()),
        );
        $pushNotificationSubscriptionEntity->setPayloadChecksum(
            $pushNotificationSubscriptionTransfer->getPayloadCheckSumOrFail(),
        );

        return $pushNotificationSubscriptionEntity;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscription> $pushNotificationSubscriptionEntities
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer $pushNotificationSubscriptionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer
     */
    public function mapPushNotificationSubscriptionEntitiesToPushNotificationSubscriptionCollectionTransfer(
        Collection $pushNotificationSubscriptionEntities,
        PushNotificationSubscriptionCollectionTransfer $pushNotificationSubscriptionCollectionTransfer
    ): PushNotificationSubscriptionCollectionTransfer {
        foreach ($pushNotificationSubscriptionEntities as $pushNotificationSubscriptionEntity) {
            $pushNotificationSubscriptionCollectionTransfer->addPushNotificationSubscription(
                $this->mapPushNotificationSubscriptionEntityToPushNotificationSubscriptionTransfer(
                    $pushNotificationSubscriptionEntity,
                    new PushNotificationSubscriptionTransfer(),
                ),
            );
        }

        return $pushNotificationSubscriptionCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscription $pushNotificationSubscriptionEntity
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function mapPushNotificationSubscriptionEntityToPushNotificationSubscriptionTransfer(
        SpyPushNotificationSubscription $pushNotificationSubscriptionEntity,
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionTransfer {
        $pushNotificationSubscriptionTransfer = $pushNotificationSubscriptionTransfer->fromArray(
            $pushNotificationSubscriptionEntity->toArray(),
            true,
        );

        $payload = $pushNotificationSubscriptionEntity->getPayload();
        if ($payload) {
            /** @var array<string, mixed> $decodedPayload */
            $decodedPayload = $this->utilEncodingService->decodeJson($payload, true);
            $pushNotificationSubscriptionTransfer->setPayload(
                $decodedPayload,
            );
        }

        return $pushNotificationSubscriptionTransfer;
    }
}
