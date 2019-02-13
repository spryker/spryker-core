<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Notification;

use Generated\Shared\Transfer\AvailabilityNotificationDataTransfer;

interface ProductBecomeAvailableNotificationSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer
     *
     * @return void
     */
    public function send(AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer): void;
}
