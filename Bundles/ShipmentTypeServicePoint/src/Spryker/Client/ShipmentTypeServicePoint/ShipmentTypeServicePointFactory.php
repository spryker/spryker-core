<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeServicePoint;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ShipmentTypeServicePoint\Dependency\Client\ShipmentTypeServicePointToServicePointStorageClientInterface;
use Spryker\Client\ShipmentTypeServicePoint\Expander\ServiceTypeExpander;
use Spryker\Client\ShipmentTypeServicePoint\Expander\ServiceTypeExpanderInterface;
use Spryker\Client\ShipmentTypeServicePoint\Reader\ServiceTypeReader;
use Spryker\Client\ShipmentTypeServicePoint\Reader\ServiceTypeReaderInterface;

class ShipmentTypeServicePointFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ShipmentTypeServicePoint\Expander\ServiceTypeExpanderInterface
     */
    public function createServiceTypeExpander(): ServiceTypeExpanderInterface
    {
        return new ServiceTypeExpander($this->createServiceTypeReader());
    }

    /**
     * @return \Spryker\Client\ShipmentTypeServicePoint\Reader\ServiceTypeReaderInterface
     */
    public function createServiceTypeReader(): ServiceTypeReaderInterface
    {
        return new ServiceTypeReader($this->getServicePointStorageClient());
    }

    /**
     * @return \Spryker\Client\ShipmentTypeServicePoint\Dependency\Client\ShipmentTypeServicePointToServicePointStorageClientInterface
     */
    public function getServicePointStorageClient(): ShipmentTypeServicePointToServicePointStorageClientInterface
    {
        return $this->getProvidedDependency(ShipmentTypeServicePointDependencyProvider::CLIENT_SERVICE_POINT_STORAGE);
    }
}
