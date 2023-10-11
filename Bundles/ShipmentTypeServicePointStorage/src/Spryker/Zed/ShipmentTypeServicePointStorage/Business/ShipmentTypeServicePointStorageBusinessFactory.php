<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentTypeServicePointStorage\Business\Expander\ServiceTypeExpander;
use Spryker\Zed\ShipmentTypeServicePointStorage\Business\Expander\ServiceTypeExpanderInterface;
use Spryker\Zed\ShipmentTypeServicePointStorage\Business\Reader\ShipmentTypeServiceTypeReader;
use Spryker\Zed\ShipmentTypeServicePointStorage\Business\Reader\ShipmentTypeServiceTypeReaderInterface;
use Spryker\Zed\ShipmentTypeServicePointStorage\Dependency\Facade\ShipmentTypeServicePointStorageToShipmentTypeServicePointFacadeInterface;
use Spryker\Zed\ShipmentTypeServicePointStorage\ShipmentTypeServicePointStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentTypeServicePointStorage\ShipmentTypeServicePointStorageConfig getConfig()
 */
class ShipmentTypeServicePointStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShipmentTypeServicePointStorage\Business\Expander\ServiceTypeExpanderInterface
     */
    public function createServiceTypeExpander(): ServiceTypeExpanderInterface
    {
        return new ServiceTypeExpander($this->createShipmentTypeServicePointReader());
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeServicePointStorage\Business\Reader\ShipmentTypeServiceTypeReaderInterface
     */
    public function createShipmentTypeServicePointReader(): ShipmentTypeServiceTypeReaderInterface
    {
        return new ShipmentTypeServiceTypeReader($this->getShipmentTypeServicePointFacade());
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeServicePointStorage\Dependency\Facade\ShipmentTypeServicePointStorageToShipmentTypeServicePointFacadeInterface
     */
    public function getShipmentTypeServicePointFacade(): ShipmentTypeServicePointStorageToShipmentTypeServicePointFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentTypeServicePointStorageDependencyProvider::FACADE_SHIPMENT_TYPE_SERVICE_POINT);
    }
}
