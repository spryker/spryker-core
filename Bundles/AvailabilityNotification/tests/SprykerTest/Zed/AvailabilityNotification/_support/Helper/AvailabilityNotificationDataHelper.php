<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityNotification\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\AvailabilitySubscriptionBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacade;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class AvailabilityNotificationDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveAvailabilitySubscriptionTransfer(array $seedData = []): AbstractTransfer
    {
        return (new AvailabilitySubscriptionBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer
     */
    public function haveAvailabilitySubscription(array $seedData = []): AbstractTransfer
    {
        $availabilitySubscription = (new AvailabilitySubscriptionBuilder($seedData))->build();

        $result = $this->getAvailabilitySubscriptionFacade()->subscribe($availabilitySubscription);

        return $result->getAvailabilitySubscription();
    }

    /**
     * @param array $seedData
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveCustomerTransfer(array $seedData = []): AbstractTransfer
    {
        return (new CustomerBuilder($seedData))->build();
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacade
     */
    protected function getAvailabilitySubscriptionFacade(): AvailabilityNotificationFacade
    {
        return $this->getLocator()->availabilityNotification()->facade();
    }
}
