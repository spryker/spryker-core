<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Dependency\Client;

class AvailabilityNotificationToCustomerAccessPermissionClientBridge implements AvailabilityNotificationToCustomerAccessPermissionClientInterface
{
    /**
     * @var \Spryker\Client\CustomerAccessPermission\CustomerAccessPermissionClientInterface
     */
    protected $customerAccessPermissionClient;

    /**
     * @param \Spryker\Client\CustomerAccessPermission\CustomerAccessPermissionClientInterface $customerAccessPermissionClient
     */
    public function __construct($customerAccessPermissionClient)
    {
        $this->customerAccessPermissionClient = $customerAccessPermissionClient;
    }

    /**
     * @return bool
     */
    public function canLoggedOutCustomerSeeProductPrice(): bool
    {
        return $this->customerAccessPermissionClient->canLoggedOutCustomerSeeProductPrice();
    }
}
