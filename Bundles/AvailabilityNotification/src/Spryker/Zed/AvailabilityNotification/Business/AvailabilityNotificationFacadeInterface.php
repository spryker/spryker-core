<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business;

use Generated\Shared\Transfer\AvailabilityNotificationDataTransfer;
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
     * - Removes provided subscription.
     * - Sends success email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function unsubscribeBySubscriptionKey(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer;

    /**
     * Specification:
     * - Removes provided subscription.
     * - Sends success email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function unsubscribeByCustomerReferenceAndSku(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer;

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
    public function sendAvailabilitySubscriptionNotification(AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer): void;

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
    public function expandCustomerTransferWithAvailabilitySubscriptionList(CustomerTransfer $customerTransfer): CustomerTransfer;
}
