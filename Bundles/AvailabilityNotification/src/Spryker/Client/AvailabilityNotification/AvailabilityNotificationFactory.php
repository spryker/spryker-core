<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityNotification;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\AvailabilityNotification\Zed\AvailabilityNotificationStub;

class AvailabilityNotificationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\AvailabilityNotification\Zed\AvailabilityNotificationStubInterface
     */
    public function createZedAvailabilityNotificationStub()
    {
        return new AvailabilityNotificationStub(
            $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::SERVICE_ZED)
        );
    }
}
