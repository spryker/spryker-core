<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscription;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationPersistenceFactory getFactory()
 */
class AvailabilityNotificationEntityManager extends AbstractEntityManager implements AvailabilityNotificationEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    public function saveAvailabilitySubscriptionFromTransfer(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void
    {
        $subscriptionEntity = new SpyAvailabilitySubscription();
        $subscriptionEntity->fromArray($availabilitySubscriptionTransfer->toArray());
        $subscriptionEntity->setFkStore($availabilitySubscriptionTransfer->getStore()->getIdStore());
        $subscriptionEntity->setFkLocale($availabilitySubscriptionTransfer->getLocale()->getIdLocale());

        $subscriptionEntity->save();
    }

    /**
     * @param string $subscriptionKey
     *
     * @return void
     */
    public function deleteBySubscriptionKey(string $subscriptionKey): void
    {
        $this->getFactory()
            ->createAvailabilitySubscriptionQuery()
            ->filterBySubscriptionKey($subscriptionKey)
            ->delete();
    }
}
