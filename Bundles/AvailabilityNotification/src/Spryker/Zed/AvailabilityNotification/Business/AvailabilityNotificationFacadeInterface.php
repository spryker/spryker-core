<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business;

use Generated\Shared\Transfer\AvailabilityNotificationCriteriaTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationDataTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface AvailabilityNotificationFacadeInterface
{
    /**
     * Specification:
     * - Requires `AvailabilityNotificationSubscription.email` to be set.
     * - Requires `AvailabilityNotificationSubscription.sku` to be set.
     * - Requires `AvailabilityNotificationSubscription.locale` to be set.
     * - Subscribe customer to product availability
     *   by provided subscription email, customer reference, product sku in a case insensitive way.
     * - Validates email.
     * - Create subscription if subscription is not created already.
     * - Uses strategy to get base URL for link generation.
     * - Sends success email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function subscribe(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): AvailabilityNotificationSubscriptionResponseTransfer;

    /**
     * Specification:
     * - Requires `AvailabilityNotificationSubscription.subscriptionKey` to be set.
     * - Requires `AvailabilityNotificationSubscription.locale` to be set.
     * - Removes provided subscription.
     * - Uses strategy to get base URL for link generation.
     * - Sends success email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribeBySubscriptionKey(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): AvailabilityNotificationSubscriptionResponseTransfer;

    /**
     * Specification:
     * - Requires `AvailabilityNotificationSubscription.customerReference` to be set.
     * - Requires `AvailabilityNotificationSubscription.sku` to be set.
     * - Requires `AvailabilityNotificationSubscription.locale` to be set.
     * - Removes provided subscription.
     * - Uses strategy to get base URL for link generation.
     * - Sends success email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribeByCustomerReferenceAndSku(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): AvailabilityNotificationSubscriptionResponseTransfer;

    /**
     * Specification:
     * - Anonymizes personal information of the provided subscription.
     * - Removes all user's subscriptions.
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
     * - Requires `AvailabilityNotificationData.store` to be set.
     * - Uses strategy to get base URL for link generation.
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

    /**
     * Specification:
     * - Finds availability subscription lists.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationCriteriaTransfer $availabilityNotificationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer
     */
    public function getAvailabilityNotifications(
        AvailabilityNotificationCriteriaTransfer $availabilityNotificationCriteriaTransfer
    ): AvailabilityNotificationSubscriptionCollectionTransfer;
}
