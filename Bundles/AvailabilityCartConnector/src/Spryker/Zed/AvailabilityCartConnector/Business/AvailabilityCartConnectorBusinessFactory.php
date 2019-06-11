<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Business;

use Spryker\Zed\AvailabilityCartConnector\AvailabilityCartConnectorDependencyProvider;
use Spryker\Zed\AvailabilityCartConnector\Business\Cart\CheckCartAvailability;
use Spryker\Zed\AvailabilityCartConnector\Dependency\Service\AvailabilityCartConnectorToUtilQuantityServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class AvailabilityCartConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AvailabilityCartConnector\Business\Cart\CheckCartAvailabilityInterface
     */
    public function createCartCheckAvailability()
    {
        return new CheckCartAvailability(
            $this->getAvailabilityFacade(),
            $this->getUtilQuantityService()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface
     */
    public function getAvailabilityFacade()
    {
        return $this->getProvidedDependency(AvailabilityCartConnectorDependencyProvider::FACADE_AVAILABILITY);
    }

    /**
     * @return \Spryker\Zed\AvailabilityCartConnector\Dependency\Service\AvailabilityCartConnectorToUtilQuantityServiceInterface
     */
    public function getUtilQuantityService(): AvailabilityCartConnectorToUtilQuantityServiceInterface
    {
        return $this->getProvidedDependency(AvailabilityCartConnectorDependencyProvider::SERVICE_UTIL_QUANTITY);
    }
}
