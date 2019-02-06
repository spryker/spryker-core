<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business;

use Generated\Shared\Transfer\AvailabilityNotificationDataTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
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
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function subscribe(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer;

    /**
     * Specification:
     * - Removes provided subscription.
     * - Sends success email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribeBySubscriptionKey(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer;

    /**
     * Specification:
     * - Removes provided subscription.
     * - Sends success email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribeByCustomerReferenceAndSku(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer;

    /**
     * Specification:
     * - Anonymizes personal information of the provided subscription.
     * - Removes all user's subscriptions
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
     * @param \Generated\Shared\Transfer\AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer
     *
     * @return void
     */
    public function sendAvailabilityNotificationSubscriptionNotification(AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer): void;

    /**
     * Specification:
     * - Finds availability subscription lists.
     * - Expands customer transfer with array.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandCustomerTransferWithAvailabilityNotificationSubscriptionList(CustomerTransfer $customerTransfer): CustomerTransfer;
}
