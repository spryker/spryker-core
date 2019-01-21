<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Dependency\Client;

interface AvailabilityNotificationToCustomerAccessPermissionClientInterface
{
    /**
     * @return bool
     */
    public function canLoggedOutCustomerSeeProductPrice(): bool;
}
