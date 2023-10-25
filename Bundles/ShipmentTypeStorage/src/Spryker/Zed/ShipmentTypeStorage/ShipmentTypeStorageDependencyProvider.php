<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToShipmentFacadeBridge;
use Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToShipmentTypeFacadeBridge;
use Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToStoreFacadeBridge;

/**
 * @method \Spryker\Zed\ShipmentTypeStorage\ShipmentTypeStorageConfig getConfig()
 */
class ShipmentTypeStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @var string
     */
    public const FACADE_SHIPMENT_TYPE = 'FACADE_SHIPMENT_TYPE';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_SHIPMENT = 'FACADE_SHIPMENT';

    /**
     * @var string
     */
    public const PLUGINS_SHIPMENT_TYPE_STORAGE_EXPANDER = 'PLUGINS_SHIPMENT_TYPE_STORAGE_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addShipmentTypeFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addShipmentTypeFacade($container);
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addShipmentFacade($container);
        $container = $this->addShipmentTypeStorageExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentTypeFacade(Container $container): Container
    {
        $container->set(static::FACADE_SHIPMENT_TYPE, function (Container $container) {
            return new ShipmentTypeStorageToShipmentTypeFacadeBridge(
                $container->getLocator()->shipmentType()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new ShipmentTypeStorageToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new ShipmentTypeStorageToStoreFacadeBridge(
                $container->getLocator()->store()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentFacade(Container $container): Container
    {
        $container->set(static::FACADE_SHIPMENT, function (Container $container) {
            return new ShipmentTypeStorageToShipmentFacadeBridge(
                $container->getLocator()->shipment()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentTypeStorageExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SHIPMENT_TYPE_STORAGE_EXPANDER, function () {
            return $this->getShipmentTypeStorageExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\ShipmentTypeStorageExtension\Dependency\Plugin\ShipmentTypeStorageExpanderPluginInterface>
     */
    protected function getShipmentTypeStorageExpanderPlugins(): array
    {
        return [];
    }
}
