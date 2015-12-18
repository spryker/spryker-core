<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\AvailabilityCheckoutConnector\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\AvailabilityCheckoutConnector\AvailabilityCheckoutConnectorDependencyProvider;
use Spryker\Zed\AvailabilityCheckoutConnector\Dependency\Facade\AvailabilityToCheckoutConnectorFacadeInterface as AvailabilityFacade;
use Spryker\Zed\AvailabilityCheckoutConnector\AvailabilityCheckoutConnectorConfig;

/**
 * @method AvailabilityCheckoutConnectorConfig getConfig()
 */
class AvailabilityCheckoutConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return AvailabilityFacade
     */
    public function getAvailabilityFacade()
    {
        return $this->getProvidedDependency(AvailabilityCheckoutConnectorDependencyProvider::FACADE_AVAILABILITY);
    }

}
