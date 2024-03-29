<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityNotification\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\AvailabilityNotificationDataBuilder;
use Generated\Shared\DataBuilder\AvailabilityNotificationSubscriptionBuilder;
use Generated\Shared\Transfer\AvailabilityNotificationDataTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacade;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class AvailabilityNotificationDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer
     */
    public function haveAvailabilityNotificationSubscriptionTransfer(
        ProductConcreteTransfer $productConcreteTransfer,
        ?CustomerTransfer $customerTransfer = null,
        array $seedData = []
    ): AvailabilityNotificationSubscriptionTransfer {
        $availabilityNotificationSubscriptionTransfer = (new AvailabilityNotificationSubscriptionBuilder($seedData))
            ->build()
            ->setSku($productConcreteTransfer->getSKU());

        if ($customerTransfer) {
            $availabilityNotificationSubscriptionTransfer->setCustomerReference($customerTransfer->getCustomerReference());
        }

        return $availabilityNotificationSubscriptionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     * @param array $seedData
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveAvailabilityNotificationSubscription(
        ProductConcreteTransfer $productConcreteTransfer,
        ?CustomerTransfer $customerTransfer = null,
        array $seedData = []
    ): AbstractTransfer {
        $availabilityNotificationSubscription = $this->haveAvailabilityNotificationSubscriptionTransfer($productConcreteTransfer, $customerTransfer, $seedData);

        $result = $this->getAvailabilityNotificationSubscriptionFacade()->subscribe($availabilityNotificationSubscription);

        return $result->getAvailabilityNotificationSubscription();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationDataTransfer
     */
    public function haveAvailabilityNotificationDataTransfer(
        ProductConcreteTransfer $productConcreteTransfer,
        array $seedData = []
    ): AvailabilityNotificationDataTransfer {
        return (new AvailabilityNotificationDataBuilder($seedData))
            ->build()
            ->setSku($productConcreteTransfer->getSku());
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacade
     */
    protected function getAvailabilityNotificationSubscriptionFacade(): AvailabilityNotificationFacade
    {
        return $this->getLocator()->availabilityNotification()->facade();
    }
}
