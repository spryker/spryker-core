<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityNotification;

use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;

interface AvailabilityNotificationClientInterface
{
    /**
     * Specification:
     * - Send Zed request
     * - Subscribe a user for product availability.
     * - Send successful email
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
     * - Send Zed request
     * - Unsubscribe a user for product availability.
     * - Send successful email
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
     * - Send Zed request
     * - Unsubscribe a user for product availability.
     * - Send successful email
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function unsubscribeByCustomerReferenceAndSku(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer;
}
