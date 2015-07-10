<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\AvailabilityCartConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\AvailabilityCartConnector\AvailabilityCartConnectorDependencyProvider;
use SprykerFeature\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityToCartConnectorFacadeInterface as AvailabilityFacade;

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
