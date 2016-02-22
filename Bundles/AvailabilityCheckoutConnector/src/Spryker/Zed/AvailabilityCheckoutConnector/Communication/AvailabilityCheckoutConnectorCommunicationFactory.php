<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCheckoutConnector\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\AvailabilityCheckoutConnector\AvailabilityCheckoutConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\AvailabilityCheckoutConnector\AvailabilityCheckoutConnectorConfig getConfig()
 */
class AvailabilityCheckoutConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\AvailabilityCheckoutConnector\Dependency\Facade\AvailabilityCheckoutConnectorToAvailabilityInterface
     */
    public function getAvailabilityFacade()
    {
        return $this->getProvidedDependency(AvailabilityCheckoutConnectorDependencyProvider::FACADE_AVAILABILITY);
    }

}
