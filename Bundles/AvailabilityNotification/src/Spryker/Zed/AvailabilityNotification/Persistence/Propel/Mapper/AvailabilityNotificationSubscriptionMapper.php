<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscription;
use Propel\Runtime\Collection\ObjectCollection;

class AvailabilityNotificationSubscriptionMapper implements AvailabilityNotificationSubscriptionMapperInterface
{
    /**
     * @param \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscription $availabilityNotificationSubscriptionEntity
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer
     */
    public function mapAvailabilityNotificationSubscriptionTransfer(
        SpyAvailabilityNotificationSubscription $availabilityNotificationSubscriptionEntity
    ): AvailabilityNotificationSubscriptionTransfer {
        $availabilityNotificationSubscriptionTransfer = new AvailabilityNotificationSubscriptionTransfer();
        $availabilityNotificationSubscriptionTransfer->fromArray($availabilityNotificationSubscriptionEntity->toArray(), true);

        $storeTransfer = new StoreTransfer();
        $storeTransfer->fromArray($availabilityNotificationSubscriptionEntity->getSpyStore()->toArray(), true);

        $availabilityNotificationSubscriptionTransfer->setStore($storeTransfer);

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->fromArray($availabilityNotificationSubscriptionEntity->getSpyLocale()->toArray(), true);

        $availabilityNotificationSubscriptionTransfer->setLocale($localeTransfer);

        return $availabilityNotificationSubscriptionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscription> $availabilityNotificationSubscriptionEntities
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer $availabilityNotificationSubscriptionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer
     */
    public function mapAvailabilityNotificationSubscriptionEntitiesToAvailabilityNotificationCollectionTransfer(
        ObjectCollection $availabilityNotificationSubscriptionEntities,
        AvailabilityNotificationSubscriptionCollectionTransfer $availabilityNotificationSubscriptionCollectionTransfer
    ): AvailabilityNotificationSubscriptionCollectionTransfer {
        foreach ($availabilityNotificationSubscriptionEntities as $availabilityNotificationSubscriptionEntity) {
            $availabilityNotificationSubscriptionCollectionTransfer->addAvailabilityNotificationSubscription(
                $this->mapAvailabilityNotificationSubscriptionTransfer($availabilityNotificationSubscriptionEntity),
            );
        }

        return $availabilityNotificationSubscriptionCollectionTransfer;
    }
}
