<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Notification;

use Generated\Shared\Transfer\AvailabilityNotificationDataTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;

interface AvailabilityNotificationSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    public function sendSubscriptionMail(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    public function sendUnsubscriptionMail(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer
     *
     * @return void
     */
    public function sendProductBecomeAvailableMail(AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer): void;
}
