<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Persistence\Mapper;

use Generated\Shared\Transfer\PushNotificationGroupCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationGroup;
use Propel\Runtime\Collection\ObjectCollection;

class PushNotificationGroupMapper
{
    /**
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotificationGroup $pushNotificationGroupEntity
     * @param \Generated\Shared\Transfer\PushNotificationGroupTransfer $pushNotificationGroupTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationGroupTransfer
     */
    public function mapPushNotificationGroupEntityToPushNotificationGroupTransfer(
        SpyPushNotificationGroup $pushNotificationGroupEntity,
        PushNotificationGroupTransfer $pushNotificationGroupTransfer
    ): PushNotificationGroupTransfer {
        return $pushNotificationGroupTransfer->fromArray($pushNotificationGroupEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationGroupTransfer $pushNotificationGroupTransfer
     * @param \Orm\Zed\PushNotification\Persistence\SpyPushNotificationGroup $pushNotificationGroupEntity
     *
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationGroup
     */
    public function mapPushNotificationGroupTransferToPushNotificationGroupEntity(
        PushNotificationGroupTransfer $pushNotificationGroupTransfer,
        SpyPushNotificationGroup $pushNotificationGroupEntity
    ): SpyPushNotificationGroup {
        return $pushNotificationGroupEntity->fromArray($pushNotificationGroupTransfer->toArray());
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\PushNotification\Persistence\SpyPushNotificationGroup> $pushNotificationGroupEntities
     * @param \Generated\Shared\Transfer\PushNotificationGroupCollectionTransfer $pushNotificationGroupCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationGroupCollectionTransfer
     */
    public function mapPushNotificationGroupEntitiesToPushNotificationGroupCollectionTransfer(
        ObjectCollection $pushNotificationGroupEntities,
        PushNotificationGroupCollectionTransfer $pushNotificationGroupCollectionTransfer
    ): PushNotificationGroupCollectionTransfer {
        foreach ($pushNotificationGroupEntities as $pushNotificationGroupEntity) {
            $pushNotificationGroupTransfer = $this->mapPushNotificationGroupEntityToPushNotificationGroupTransfer(
                $pushNotificationGroupEntity,
                new PushNotificationGroupTransfer(),
            );
            $pushNotificationGroupCollectionTransfer->addGroup($pushNotificationGroupTransfer);
        }

        return $pushNotificationGroupCollectionTransfer;
    }
}
