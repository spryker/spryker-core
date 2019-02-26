<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Anonymizer;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface;

class AvailabilityNotificationSubscriptionAnonymizer implements AvailabilityNotificationSubscriptionAnonymizerInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface $entityManager
     */
    public function __construct(AvailabilityNotificationEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function anonymizeSubscription(CustomerTransfer $customerTransfer): AvailabilityNotificationSubscriptionResponseTransfer
    {
        $customerTransfer->requireCustomerReference();

        $this->entityManager->deleteByCustomerReference($customerTransfer->getCustomerReference());

        return (new AvailabilityNotificationSubscriptionResponseTransfer())->setIsSuccess(true);
    }
}
