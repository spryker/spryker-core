<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityNotification;

use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\AvailabilityNotification\AvailabilityNotificationFactory getFactory()
 */
class AvailabilityNotificationClient extends AbstractClient implements AvailabilityNotificationClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function subscribe(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        return $this->getFactory()->createZedAvailabilityNotificationStub()->subscribe($availabilityNotificationSubscriptionTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function unsubscribe(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        return $this->getFactory()->createZedAvailabilityNotificationStub()->unsubscribe($availabilityNotificationSubscriptionTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function checkSubscription(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        return $this->getFactory()->createZedAvailabilityNotificationStub()->checkSubscription($availabilityNotificationSubscriptionTransfer);
    }
}
