<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationBusinessFactory getFactory()
 */
class AvailabilityNotificationFacade extends AbstractFacade implements AvailabilityNotificationFacadeInterface
{
    /**
     * Specification:
     * - Subscribe by provided subscription email, customer reference, product sku in a case insensitive way.
     * - Adds subscription:
     *      - Validates email.
     *      - Create subscription if subscription is not created already.
     *      - Sends confirmation email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function subscribe(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        $subscriptionResponse = $this->getFactory()
            ->createSubscriptionHandler()
            ->processAvailabilitySubscription($availabilityNotificationSubscriptionTransfer);

        return $subscriptionResponse;
    }

    /**
     * Specification:
     * - Checks if the provided subscription is already existing.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function checkSubscription(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        $subscriptionResponse = $this->getFactory()
            ->createSubscriptionHandler()
            ->checkAvailabilitySubscription($availabilityNotificationSubscriptionTransfer);

        return $subscriptionResponse;
    }

    /**
     * Specification:
     * - Remove provided subscription.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function unsubscribe(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        $subscriptionResponse = $this->getFactory()
            ->createSubscriptionHandler()
            ->processAvailabilityNotificationUnsubscription($availabilityNotificationSubscriptionTransfer);

        return $subscriptionResponse;
    }

    /**
     * Specification:
     * - Anonymizes personal information of the provided subscription.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return void
     */
    public function anonymizeSubscription(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): void
    {
    }
}
