<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\CustomerExpander;

use ArrayObject;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionReaderInterface;

class CustomerExpander implements CustomerExpanderInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionReaderInterface
     */
    protected $availabilitySubscriptionReader;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionReaderInterface $availabilitySubscriptionReader
     */
    public function __construct(AvailabilitySubscriptionReaderInterface $availabilitySubscriptionReader)
    {
        $this->availabilitySubscriptionReader = $availabilitySubscriptionReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandCustomerTransferWithAvailabilitySubscriptionList(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $availabilitySubscriptions = $this->availabilitySubscriptionReader->findByCustomerReference($customerTransfer->getCustomerReference());
        $customerTransfer->setAvailabilitySubscriptions(new ArrayObject($availabilitySubscriptions));

        return $customerTransfer;
    }
}
