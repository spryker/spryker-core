<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;

interface AvailabilityNotificationEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    public function saveAvailabilitySubscriptionFromTransfer(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void;

    /**
     * @param string $subscriptionKey
     *
     * @return void
     */
    public function deleteBySubscriptionKey(string $subscriptionKey): void;
}
