<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
