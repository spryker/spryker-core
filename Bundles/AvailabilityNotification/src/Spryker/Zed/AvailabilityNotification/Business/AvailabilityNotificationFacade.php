<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationBusinessFactory getFactory()
 */
class AvailabilityNotificationFacade extends AbstractFacade implements AvailabilityNotificationFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function subscribe(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer)
    {
        $subscriptionResponse = $this->getFactory()
            ->createSubscriptionHandler()
            ->processAvailabilityNotificationSubscription($availabilityNotificationSubscriptionTransfer);

        return $subscriptionResponse;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function checkSubscription(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer)
    {
        $subscriptionResponse = $this->getFactory()
            ->createSubscriptionHandler()
            ->checkAvailabilityNotificationSubscription($availabilityNotificationSubscriptionTransfer);

        return $subscriptionResponse;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribe(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer)
    {
        $subscriptionResponse = $this->getFactory()
            ->createSubscriptionHandler()
            ->processAvailabilityNotificationUnsubscription($availabilityNotificationSubscriptionTransfer);

        return $subscriptionResponse;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return void
     */
    public function anonymizeSubscription(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer)
    {
        $this->getFactory()
            ->createSubscriptionAnonymizer()
            ->process($availabilityNotificationSubscriptionTransfer);
    }
}
