<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscription;

interface SubscriptionManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return void
     */
    public function subscribe(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return bool
     */
    public function isAlreadySubscribed(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return bool
     */
    public function unsubscribe(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscription
     */
    public function createSubscriptionEntityFromTransfer(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): SpyAvailabilitySubscription;
}
