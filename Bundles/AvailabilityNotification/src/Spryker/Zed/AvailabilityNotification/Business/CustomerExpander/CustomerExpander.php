<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\CustomerExpander;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionReaderInterface;

class CustomerExpander implements CustomerExpanderInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionReaderInterface
     */
    protected $availabilityNotificationSubscriptionReader;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionReaderInterface $availabilityNotificationSubscriptionReader
     */
    public function __construct(AvailabilityNotificationSubscriptionReaderInterface $availabilityNotificationSubscriptionReader)
    {
        $this->availabilityNotificationSubscriptionReader = $availabilityNotificationSubscriptionReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandCustomerTransferWithAvailabilityNotificationSubscriptionList(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $availabilityNotificationSubscriptions = $this->availabilityNotificationSubscriptionReader->findByCustomerReference($customerTransfer->getCustomerReference());
        $skus = [];

        foreach ($availabilityNotificationSubscriptions as $availabilityNotificationSubscription) {
            $skus[] = $availabilityNotificationSubscription->getSku();
        }

        $customerTransfer->setAvailabilityNotificationSubscriptionSkus($skus);

        return $customerTransfer;
    }
}
