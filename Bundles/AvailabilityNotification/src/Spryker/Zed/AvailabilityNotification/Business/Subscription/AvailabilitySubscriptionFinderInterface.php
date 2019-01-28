<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionRequestTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;

interface AvailabilitySubscriptionFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function findAvailabilitySubscription(AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionRequestTransfer): AvailabilitySubscriptionResponseTransfer;
}
