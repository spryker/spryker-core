<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;

interface AvailabilityNotificationFacadeInterface
{
    /**
     * Specification:
     * - Identifies subscription by provided subscription email in a case insensitive way.
     * - Adds subscription to each provided AvailabilityNotification type:
     *      - Validates email.
     *      - Registers subscription if subscription is not registered already.
     *      - Sends confirmation email.
     *      - Sets subscription as confirmed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function subscribe(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer);

    /**
     * Specification:
     * - Checks if the provided subscription is subscribed to any of the provided AvailabilityNotification type using case insensitive email matching.
     * - Returns with a list, each element contains the result for a AvailabilityNotification type.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function checkSubscription(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer);

    /**
     * Specification:
     * - Unsubscribes provided subscription from provided AvailabilityNotification type list using case insensitive email matching.
     * - Sends unsubscribed mail for each AvailabilityNotification type.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribe(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer);

    /**
     * Specification:
     * - Unsubscribes provided subscription from provided AvailabilityNotification types.
     * - Anonymizes personal information of the provided subscription.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return void
     */
    public function anonymizeSubscription(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer);
}
