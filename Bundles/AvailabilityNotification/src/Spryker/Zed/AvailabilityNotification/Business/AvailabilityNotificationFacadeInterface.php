<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business;

use Generated\Shared\Transfer\AvailabilitySubscriptionExistenceRequestTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionExistenceResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\StoreTransfer;

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
     * - Checks if the provided subscription is already existing.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceResponseTransfer
     */
    public function checkExistence(AvailabilitySubscriptionExistenceRequestTransfer $availabilitySubscriptionExistenceRequestTransfer): AvailabilitySubscriptionExistenceResponseTransfer;

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
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    public function anonymizeSubscription(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void;

    /**
     * Specification:
     * - Find availability notification by email and sku for current store.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findAvailabilitySubscription(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): ?AvailabilitySubscriptionTransfer;

    /**
     * Specification:
     * - Send mails to all users which subscribed to product availability notification.
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function processAvailabilityNotificationSubscription(string $sku, StoreTransfer $storeTransfer): void;
}
