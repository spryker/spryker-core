<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityNotification\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\AvailabilitySubscriptionBuilder;
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
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveAvailabilitySubscriptionTransfer(ProductConcreteTransfer $productConcreteTransfer, ?CustomerTransfer $customerTransfer = null, array $seedData = []): AbstractTransfer
    {
        $availabilitySubscriptionTransfer = (new AvailabilitySubscriptionBuilder($seedData))
            ->build()
            ->setSku($productConcreteTransfer->getSKU());

        if ($customerTransfer) {
            $availabilitySubscriptionTransfer->setCustomerReference($customerTransfer->getCustomerReference());
        }

        return $availabilitySubscriptionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     * @param array $seedData
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveAvailabilitySubscription(ProductConcreteTransfer $productConcreteTransfer, ?CustomerTransfer $customerTransfer = null, array $seedData = []): AbstractTransfer
    {
        $availabilitySubscription = $this->haveAvailabilitySubscriptionTransfer($productConcreteTransfer, $customerTransfer, $seedData);

        $result = $this->getAvailabilitySubscriptionFacade()->subscribe($availabilitySubscription);

        return $result->getAvailabilitySubscription();
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacade
     */
    protected function getAvailabilitySubscriptionFacade(): AvailabilityNotificationFacade
    {
        return $this->getLocator()->availabilityNotification()->facade();
    }
}
