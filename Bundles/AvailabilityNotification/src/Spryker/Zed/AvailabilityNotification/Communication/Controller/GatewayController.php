<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Communication\Controller;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function subscribeAction(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer
    {
        return $this->getFacade()->subscribe($availabilityNotificationSubscriptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribeAction(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer
    {
        return $this->getFacade()->unsubscribe($availabilityNotificationSubscriptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function checkSubscriptionAction(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer
    {
        return $this->getFacade()->checkSubscription($availabilityNotificationSubscriptionTransfer);
    }
}
