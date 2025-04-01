<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SspServiceManagement;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use SprykerFeature\Client\SspServiceManagement\Expander\ShipmentTypeProductViewExpander;
use SprykerFeature\Client\SspServiceManagement\Expander\ShipmentTypeProductViewExpanderInterface;
use SprykerFeature\Client\SspServiceManagement\Reader\ShipmentTypeStorageReader;
use SprykerFeature\Client\SspServiceManagement\Reader\ShipmentTypeStorageReaderInterface;
use SprykerFeature\Client\SspServiceManagement\Zed\SspServiceManagementStub;
use SprykerFeature\Client\SspServiceManagement\Zed\SspServiceManagementStubInterface;

class SspServiceManagementFactory extends AbstractFactory
{
    /**
     * @return \SprykerFeature\Client\SspServiceManagement\Zed\SspServiceManagementStubInterface
     */
    public function createSspServiceManagementStub(): SspServiceManagementStubInterface
    {
        return new SspServiceManagementStub(
            $this->getProvidedDependency(SspServiceManagementDependencyProvider::CLIENT_ZED_REQUEST),
        );
    }

    /**
     * @return \SprykerFeature\Client\SspServiceManagement\Reader\ShipmentTypeStorageReaderInterface
     */
    public function createShipmentTypeStorageReader(): ShipmentTypeStorageReaderInterface
    {
        return new ShipmentTypeStorageReader($this->getShipmentTypeStorageClient(), $this->getStoreClient());
    }

    /**
     * @return \SprykerFeature\Client\SspServiceManagement\Expander\ShipmentTypeProductViewExpanderInterface
     */
    public function createShipmentTypeProductViewExpander(): ShipmentTypeProductViewExpanderInterface
    {
        return new ShipmentTypeProductViewExpander($this->createShipmentTypeStorageReader());
    }

    /**
     * @return \Spryker\Client\Store\StoreClientInterface
     */
    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface
     */
    public function getShipmentTypeStorageClient(): ShipmentTypeStorageClientInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::CLIENT_SHIPMENT_TYPE_STORAGE);
    }
}
