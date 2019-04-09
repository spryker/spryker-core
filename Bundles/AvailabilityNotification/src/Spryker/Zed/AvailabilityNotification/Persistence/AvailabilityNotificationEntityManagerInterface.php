<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;

interface AvailabilityNotificationEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return void
     */
    public function saveAvailabilityNotificationSubscription(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): void;

    /**
     * @param string $subscriptionKey
     *
     * @return void
     */
    public function deleteBySubscriptionKey(string $subscriptionKey): void;

    /**
     * @param string $customerReference
     *
     * @return void
     */
    public function deleteByCustomerReference(string $customerReference): void;
}
