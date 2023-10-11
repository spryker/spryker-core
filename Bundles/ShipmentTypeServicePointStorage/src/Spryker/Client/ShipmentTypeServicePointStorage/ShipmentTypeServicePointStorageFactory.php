<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeServicePointStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ShipmentTypeServicePointStorage\Dependency\Client\ShipmentTypeServicePointStorageToServicePointStorageClientInterface;
use Spryker\Client\ShipmentTypeServicePointStorage\Expander\ServiceTypeExpander;
use Spryker\Client\ShipmentTypeServicePointStorage\Expander\ServiceTypeExpanderInterface;
use Spryker\Client\ShipmentTypeServicePointStorage\Reader\ServiceTypeReader;
use Spryker\Client\ShipmentTypeServicePointStorage\Reader\ServiceTypeReaderInterface;

class ShipmentTypeServicePointStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ShipmentTypeServicePointStorage\Expander\ServiceTypeExpanderInterface
     */
    public function createServiceTypeExpander(): ServiceTypeExpanderInterface
    {
        return new ServiceTypeExpander($this->createServiceTypeReader());
    }

    /**
     * @return \Spryker\Client\ShipmentTypeServicePointStorage\Reader\ServiceTypeReaderInterface
     */
    public function createServiceTypeReader(): ServiceTypeReaderInterface
    {
        return new ServiceTypeReader($this->getServicePointStorageClient());
    }

    /**
     * @return \Spryker\Client\ShipmentTypeServicePointStorage\Dependency\Client\ShipmentTypeServicePointStorageToServicePointStorageClientInterface
     */
    public function getServicePointStorageClient(): ShipmentTypeServicePointStorageToServicePointStorageClientInterface
    {
        return $this->getProvidedDependency(ShipmentTypeServicePointStorageDependencyProvider::CLIENT_SERVICE_POINT_STORAGE);
    }
}
