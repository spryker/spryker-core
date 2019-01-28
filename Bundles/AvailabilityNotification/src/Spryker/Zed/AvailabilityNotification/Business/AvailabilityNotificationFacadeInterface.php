<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business;

use Generated\Shared\Transfer\AvailabilityNotificationTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionRequestTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface AvailabilityNotificationFacadeInterface
{
    /**
     * Specification:
     * - Subscribe customer to product availability
     *   by provided subscription email, customer reference, product sku in a case insensitive way.
     * - Validates email.
     * - Create subscription if subscription is not created already.
     * - Sends success email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function subscribe(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer;

    /**
     * Specification:
     * - Find a availability subscription
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function findAvailabilitySubscription(AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionExistenceRequestTransfer): AvailabilitySubscriptionResponseTransfer;

    /**
     * Specification:
     * - Removes provided subscription.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function unsubscribe(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer;

    /**
     * Specification:
     * - Anonymizes personal information of the provided subscription.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function anonymizeSubscription(CustomerTransfer $customerTransfer): void;

    /**
     * Specification:
     * - Send mails to all users which subscribed to product availability notification.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationTransfer $availabilityNotificationTransfer
     *
     * @return void
     */
    public function sendAvailabilitySubscriptionNotification(AvailabilityNotificationTransfer $availabilityNotificationTransfer): void;
}
