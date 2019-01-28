<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityNotification;

use Generated\Shared\Transfer\AvailabilitySubscriptionRequestTransfer;
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
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function subscribe(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        return $this->getFactory()
            ->createZedAvailabilityNotificationStub()
            ->subscribe($availabilitySubscriptionTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function unsubscribe(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        return $this->getFactory()
            ->createZedAvailabilityNotificationStub()
            ->unsubscribe($availabilitySubscriptionTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function findAvailabilitySubscription(AvailabilitySubscriptionRequestTransfer $availabilitySubscriptionRequestTransfer): AvailabilitySubscriptionResponseTransfer
    {
        return $this->getFactory()
            ->createZedAvailabilityNotificationStub()
            ->findAvailabilitySubscription($availabilitySubscriptionRequestTransfer);
    }
}
