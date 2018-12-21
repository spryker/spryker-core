<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityNotification\Zed;

use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Spryker\Client\AvailabilityNotification\Dependency\Client\AvailabilityNotificationToZedRequestClientInterface;

class AvailabilityNotificationStub implements AvailabilityNotificationStubInterface
{
    /**
     * @var \Spryker\Client\AvailabilityNotification\Dependency\Client\AvailabilityNotificationToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\AvailabilityNotification\Dependency\Client\AvailabilityNotificationToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(AvailabilityNotificationToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function subscribe(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer $availabilityNotificationResponseTransfer */
        $availabilityNotificationResponseTransfer = $this->zedRequestClient->call('/availability-notification/gateway/subscribe', $availabilityNotificationSubscriptionTransfer);

        return $availabilityNotificationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function unsubscribe(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer $availabilityNotificationResponseTransfer */
        $availabilityNotificationResponseTransfer = $this->zedRequestClient->call('/availability-notification/gateway/unsubscribe', $availabilityNotificationSubscriptionTransfer);

        return $availabilityNotificationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function checkSubscription(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer $availabilityNotificationResponseTransfer */
        $availabilityNotificationResponseTransfer = $this->zedRequestClient->call('/availability-notification/gateway/check-subscription', $availabilityNotificationSubscriptionTransfer);

        return $availabilityNotificationResponseTransfer;
    }
}
