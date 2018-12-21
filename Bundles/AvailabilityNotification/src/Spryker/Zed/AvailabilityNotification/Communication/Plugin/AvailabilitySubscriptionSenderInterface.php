<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Communication\Plugin;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;

interface AvailabilitySubscriptionSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return bool
     */
    public function send(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): bool;
}
