<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\CustomerExpander;

use Generated\Shared\Transfer\AvailabilityNotificationCriteriaTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionReaderInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface;

class CustomerExpander implements CustomerExpanderInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionReaderInterface
     */
    protected $availabilityNotificationSubscriptionReader;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionReaderInterface $availabilityNotificationSubscriptionReader
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        AvailabilityNotificationSubscriptionReaderInterface $availabilityNotificationSubscriptionReader,
        AvailabilityNotificationToStoreFacadeInterface $storeFacade
    ) {
        $this->availabilityNotificationSubscriptionReader = $availabilityNotificationSubscriptionReader;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandCustomerTransferWithAvailabilityNotificationSubscriptionList(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $customerTransfer->requireCustomerReference();

        $availabilityNotificationSubscriptions = $this
                            ->availabilityNotificationSubscriptionReader
                            ->getAvailabilityNotifications(
                                (new AvailabilityNotificationCriteriaTransfer())
                                    ->addCustomerReference($customerTransfer->getCustomerReference())
                                    ->addStoreName($this->storeFacade->getCurrentStore()->getName())
                            )
                            ->getAvailabilityNotificationSubscriptions();
        $skus = [];

        foreach ($availabilityNotificationSubscriptions as $availabilityNotificationSubscription) {
            $skus[] = $availabilityNotificationSubscription->getSku();
        }

        $customerTransfer->setAvailabilityNotificationSubscriptionSkus($skus);

        return $customerTransfer;
    }
}
