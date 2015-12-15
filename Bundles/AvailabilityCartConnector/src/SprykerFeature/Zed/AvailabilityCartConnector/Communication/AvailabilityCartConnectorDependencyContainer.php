<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\AvailabilityCartConnector\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Spryker\Zed\AvailabilityCartConnector\AvailabilityCartConnectorDependencyProvider;
use Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityToCartConnectorFacadeInterface as AvailabilityFacade;

class AvailabilityCartConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return AvailabilityFacade
     */
    public function getAvailabilityFacade()
    {
        return $this->getProvidedDependency(AvailabilityCartConnectorDependencyProvider::FACADE_AVAILABILITY);
    }

}
