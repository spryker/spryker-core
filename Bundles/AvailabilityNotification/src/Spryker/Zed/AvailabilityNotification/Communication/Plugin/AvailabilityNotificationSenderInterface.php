<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Communication\Plugin;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;

interface AvailabilityNotificationSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    public function sendSubscribedMail(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    public function sendUnsubscribedMail(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void;
}
