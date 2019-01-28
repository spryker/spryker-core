<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilityNotificationTransfer;
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
     * @param \Generated\Shared\Transfer\AvailabilityNotificationTransfer $availabilityNotificationTransfer
     *
     * @return void
     */
    public function sendProductBecomeAvailableMail(AvailabilityNotificationTransfer $availabilityNotificationTransfer): void;
}
