<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityNotification\Zed;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
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
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function subscribe(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer $availabilityNotificationResponseTransfer */
        $availabilityNotificationResponseTransfer = $this->zedRequestClient->call('/availability-notification/gateway/subscribe', $availabilityNotificationSubscriptionTransfer);

        return $availabilityNotificationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribeBySubscriptionKey(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer $availabilityNotificationResponseTransfer */
        $availabilityNotificationResponseTransfer = $this->zedRequestClient->call('/availability-notification/gateway/unsubscribe-by-subscription-key', $availabilityNotificationSubscriptionTransfer);

        return $availabilityNotificationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribeByCustomerReferenceAndSku(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer $availabilityNotificationResponseTransfer */
        $availabilityNotificationResponseTransfer = $this->zedRequestClient->call('/availability-notification/gateway/unsubscribe-by-customer-reference-and-sku', $availabilityNotificationSubscriptionTransfer);

        return $availabilityNotificationResponseTransfer;
    }
}
