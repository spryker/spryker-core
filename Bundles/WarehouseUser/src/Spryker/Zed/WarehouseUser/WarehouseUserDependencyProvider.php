<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToStockFacadeBridge;
use Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToUserFacadeBridge;

/**
 * @method \Spryker\Zed\WarehouseUser\WarehouseUserConfig getConfig()
 */
class WarehouseUserDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_USER = 'FACADE_USER';

    /**
     * @var string
     */
    public const FACADE_STOCK = 'FACADE_STOCK';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addUserFacade($container);
        $container = $this->addStockFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER, function (Container $container) {
            return new WarehouseUserToUserFacadeBridge($container->getLocator()->user()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStockFacade(Container $container): Container
    {
        $container->set(static::FACADE_STOCK, function (Container $container) {
            return new WarehouseUserToStockFacadeBridge($container->getLocator()->stock()->facade());
        });

        return $container;
    }
}
