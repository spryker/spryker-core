<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePoint\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentTypeServicePoint\Business\Expander\ServiceTypeExpander;
use Spryker\Zed\ShipmentTypeServicePoint\Business\Expander\ServiceTypeExpanderInterface;
use Spryker\Zed\ShipmentTypeServicePoint\Dependency\Facade\ShipmentTypeServicePointToServicePointFacadeInterface;
use Spryker\Zed\ShipmentTypeServicePoint\ShipmentTypeServicePointDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentTypeServicePoint\ShipmentTypeServicePointConfig getConfig()
 * @method \Spryker\Zed\ShipmentTypeServicePoint\Persistence\ShipmentTypeServicePointRepositoryInterface getRepository()
 */
class ShipmentTypeServicePointBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShipmentTypeServicePoint\Business\Expander\ServiceTypeExpanderInterface
     */
    public function createServiceTypeExpander(): ServiceTypeExpanderInterface
    {
        return new ServiceTypeExpander(
            $this->getRepository(),
            $this->getServicePointFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeServicePoint\Dependency\Facade\ShipmentTypeServicePointToServicePointFacadeInterface
     */
    public function getServicePointFacade(): ShipmentTypeServicePointToServicePointFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentTypeServicePointDependencyProvider::FACADE_SERVICE_POINT);
    }
}
