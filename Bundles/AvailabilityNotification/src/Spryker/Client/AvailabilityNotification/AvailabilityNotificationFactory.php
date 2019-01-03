<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityNotification;

use Spryker\Client\AvailabilityNotification\Dependency\Client\AvailabilityNotificationToZedRequestClientInterface;
use Spryker\Client\AvailabilityNotification\Zed\AvailabilityNotificationStub;
use Spryker\Client\AvailabilityNotification\Zed\AvailabilityNotificationStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class AvailabilityNotificationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\AvailabilityNotification\Zed\AvailabilityNotificationStubInterface
     */
    public function createZedAvailabilityNotificationStub(): AvailabilityNotificationStubInterface
    {
        return new AvailabilityNotificationStub($this->getAvailabilityNotificationToZedRequestClient());
    }

    /**
     * @return \Spryker\Client\AvailabilityNotification\Dependency\Client\AvailabilityNotificationToZedRequestClientInterface
     */
    public function getAvailabilityNotificationToZedRequestClient(): AvailabilityNotificationToZedRequestClientInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
