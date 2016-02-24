<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Communication;

use Spryker\Zed\AvailabilityCartConnector\AvailabilityCartConnectorDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class AvailabilityCartConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface
     */
    public function getAvailabilityFacade()
    {
        return $this->getProvidedDependency(AvailabilityCartConnectorDependencyProvider::FACADE_AVAILABILITY);
    }

}
