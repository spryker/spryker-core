<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Notification;

use Generated\Shared\Transfer\AvailabilityNotificationDataTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;

interface AvailabilityNotificationSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return void
     */
    public function sendSubscriptionMail(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return void
     */
    public function sendUnsubscriptionMail(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer
     *
     * @return void
     */
    public function sendProductBecomeAvailableMail(AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer): void;
}
