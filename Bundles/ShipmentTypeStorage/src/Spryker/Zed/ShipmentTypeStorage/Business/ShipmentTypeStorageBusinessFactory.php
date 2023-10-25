<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentTypeStorage\Business\Expander\ShipmentTypeStorageExpander;
use Spryker\Zed\ShipmentTypeStorage\Business\Expander\ShipmentTypeStorageExpanderInterface;
use Spryker\Zed\ShipmentTypeStorage\Business\Mapper\ShipmentTypeStorageMapper;
use Spryker\Zed\ShipmentTypeStorage\Business\Mapper\ShipmentTypeStorageMapperInterface;
use Spryker\Zed\ShipmentTypeStorage\Business\Reader\ShipmentMethodReader;
use Spryker\Zed\ShipmentTypeStorage\Business\Reader\ShipmentMethodReaderInterface;
use Spryker\Zed\ShipmentTypeStorage\Business\Writer\ShipmentTypeStorageWriter;
use Spryker\Zed\ShipmentTypeStorage\Business\Writer\ShipmentTypeStorageWriterInterface;
use Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToShipmentFacadeInterface;
use Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToShipmentTypeFacadeInterface;
use Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToStoreFacadeInterface;
use Spryker\Zed\ShipmentTypeStorage\ShipmentTypeStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentTypeStorage\ShipmentTypeStorageConfig getConfig()
 * @method \Spryker\Zed\ShipmentTypeStorage\Persistence\ShipmentTypeStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ShipmentTypeStorage\Persistence\ShipmentTypeStorageRepositoryInterface getRepository()
 */
class ShipmentTypeStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShipmentTypeStorage\Business\Writer\ShipmentTypeStorageWriterInterface
     */
    public function createShipmentTypeStorageWriter(): ShipmentTypeStorageWriterInterface
    {
        return new ShipmentTypeStorageWriter(
            $this->getEntityManager(),
            $this->createShipmentMethodReader(),
            $this->createShipmentTypeStorageMapper(),
            $this->createShipmentTypeStorageExpander(),
            $this->getShipmentTypeFacade(),
            $this->getStoreFacade(),
            $this->getEventBehaviorFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeStorage\Business\Mapper\ShipmentTypeStorageMapperInterface
     */
    public function createShipmentTypeStorageMapper(): ShipmentTypeStorageMapperInterface
    {
        return new ShipmentTypeStorageMapper();
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeStorage\Business\Expander\ShipmentTypeStorageExpanderInterface
     */
    public function createShipmentTypeStorageExpander(): ShipmentTypeStorageExpanderInterface
    {
        return new ShipmentTypeStorageExpander(
            $this->createShipmentMethodReader(),
            $this->getShipmentTypeStorageExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeStorage\Business\Reader\ShipmentMethodReaderInterface
     */
    public function createShipmentMethodReader(): ShipmentMethodReaderInterface
    {
        return new ShipmentMethodReader($this->getShipmentFacade());
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ShipmentTypeStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentTypeStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToShipmentTypeFacadeInterface
     */
    public function getShipmentTypeFacade(): ShipmentTypeStorageToShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentTypeStorageDependencyProvider::FACADE_SHIPMENT_TYPE);
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToStoreFacadeInterface
     */
    public function getStoreFacade(): ShipmentTypeStorageToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentTypeStorageDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToShipmentFacadeInterface
     */
    public function getShipmentFacade(): ShipmentTypeStorageToShipmentFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentTypeStorageDependencyProvider::FACADE_SHIPMENT);
    }

    /**
     * @return list<\Spryker\Zed\ShipmentTypeStorageExtension\Dependency\Plugin\ShipmentTypeStorageExpanderPluginInterface>
     */
    public function getShipmentTypeStorageExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ShipmentTypeStorageDependencyProvider::PLUGINS_SHIPMENT_TYPE_STORAGE_EXPANDER);
    }
}
