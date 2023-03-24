<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList;

use Propel\Runtime\Propel;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PickingList\Dependency\External\PickingListToPropelDatabaseConnectionAdapter;
use Spryker\Zed\PickingList\Dependency\Facade\PickingListToSalesFacadeBridge;
use Spryker\Zed\PickingList\Dependency\Facade\PickingListToSalesFacadeInterface;
use Spryker\Zed\PickingList\Dependency\Facade\PickingListToWarehouseUserFacadeBridge;
use Spryker\Zed\PickingList\Dependency\Facade\PickingListToWarehouseUserInterface;

/**
 * @method \Spryker\Zed\PickingList\PickingListConfig getConfig()
 */
class PickingListDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_SALES = 'FACADE_SALES';

    /**
     * @var string
     */
    public const FACADE_WAREHOUSE_USER = 'FACADE_WAREHOUSE_USER';

    /**
     * @var string
     */
    public const CONNECTION_DATABASE = 'CONNECTION_DATABASE';

    /**
     * @var string
     */
    public const PLUGINS_PICKING_LIST_POST_CREATE = 'PLUGINS_PICKING_LIST_POST_CREATE';

    /**
     * @var string
     */
    public const PLUGINS_PICKING_LIST_POST_UPDATE = 'PLUGINS_PICKING_LIST_POST_UPDATE';

    /**
     * @var string
     */
    public const PLUGINS_PICKING_LIST_GENERATOR_STRATEGY = 'PLUGINS_PICKING_LIST_GENERATOR_STRATEGY';

    /**
     * @var string
     */
    public const PLUGINS_PICKING_LIST_COLLECTION_EXPANDER = 'PLUGINS_PICKING_LIST_COLLECTION_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addWarehouseUserFacade($container);
        $container = $this->addDatabaseConnection($container);
        $container = $this->addPickingListPostCreatePlugins($container);
        $container = $this->addPickingListPostUpdatePlugins($container);
        $container = $this->addPickingListGeneratorStrategyPlugins($container);
        $container = $this->addPickingListCollectionExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addSalesFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addWarehouseUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_WAREHOUSE_USER, function (Container $container): PickingListToWarehouseUserInterface {
            return new PickingListToWarehouseUserFacadeBridge(
                $container->getLocator()->warehouseUser()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDatabaseConnection(Container $container): Container
    {
        $container->set(static::CONNECTION_DATABASE, function () {
            return new PickingListToPropelDatabaseConnectionAdapter(Propel::getConnection());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container): PickingListToSalesFacadeInterface {
            return new PickingListToSalesFacadeBridge(
                $container->getLocator()->sales()->facade(),
            );
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostCreatePluginInterface>
     */
    protected function getPickingListPostCreatePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPickingListPostCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PICKING_LIST_POST_CREATE, function (): array {
            return $this->getPickingListPostCreatePlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostUpdatePluginInterface>
     */
    protected function getPickingListPostUpdatePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPickingListPostUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PICKING_LIST_POST_UPDATE, function (): array {
            return $this->getPickingListPostUpdatePlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListGeneratorStrategyPluginInterface>
     */
    protected function getPickingListGeneratorStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPickingListGeneratorStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PICKING_LIST_GENERATOR_STRATEGY, function (): array {
            return $this->getPickingListGeneratorStrategyPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPickingListCollectionExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PICKING_LIST_COLLECTION_EXPANDER, function () {
            return $this->getPickingListCollectionExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListCollectionExpanderPluginInterface>
     */
    protected function getPickingListCollectionExpanderPlugins(): array
    {
        return [];
    }
}
