<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Persistence\Mapper;

use Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationProvider;
use Propel\Runtime\Collection\ObjectCollection;

class PushNotificationProviderMapper
{
    /**
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotificationProvider $pushNotificationProviderEntity
     * @param \Generated\Shared\Transfer\PushNotificationProviderTransfer $pushNotificationProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderTransfer
     */
    public function mapPushNotificationProviderEntityToPushNotificationProviderTransfer(
        SpyPushNotificationProvider $pushNotificationProviderEntity,
        PushNotificationProviderTransfer $pushNotificationProviderTransfer
    ): PushNotificationProviderTransfer {
        return $pushNotificationProviderTransfer->fromArray($pushNotificationProviderEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderTransfer $pushNotificationProviderTransfer
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotificationProvider $pushNotificationProviderEntity
     *
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationProvider
     */
    public function mapPushNotificationProviderTransferToPushNotificationProviderEntity(
        PushNotificationProviderTransfer $pushNotificationProviderTransfer,
        SpyPushNotificationProvider $pushNotificationProviderEntity
    ): SpyPushNotificationProvider {
        return $pushNotificationProviderEntity->fromArray($pushNotificationProviderTransfer->toArray());
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\PushNotification\Persistence\SpyPushNotificationProvider> $pushNotificationProviderEntities
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer $pushNotificationProviderCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer
     */
    public function mapPushNotificationProviderEntitiesToPushNotificationProviderCollectionTransfer(
        ObjectCollection $pushNotificationProviderEntities,
        PushNotificationProviderCollectionTransfer $pushNotificationProviderCollectionTransfer
    ): PushNotificationProviderCollectionTransfer {
        foreach ($pushNotificationProviderEntities as $pushNotificationProviderEntity) {
            $pushNotificationProviderTransfer = $this->mapPushNotificationProviderEntityToPushNotificationProviderTransfer(
                $pushNotificationProviderEntity,
                new PushNotificationProviderTransfer(),
            );
            $pushNotificationProviderCollectionTransfer->addProvider($pushNotificationProviderTransfer);
        }

        return $pushNotificationProviderCollectionTransfer;
    }
}
