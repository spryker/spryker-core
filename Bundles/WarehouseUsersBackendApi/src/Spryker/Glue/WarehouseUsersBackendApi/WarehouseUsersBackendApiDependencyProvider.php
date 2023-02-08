<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade\WarehouseUsersBackendApiToUserFacadeBridge;
use Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade\WarehouseUsersBackendApiToWarehouseUserFacadeBridge;

/**
 * @method \Spryker\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiConfig getConfig()
 */
class WarehouseUsersBackendApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_WAREHOUSE_USER = 'FACADE_WAREHOUSE_USER';

    /**
     * @var string
     */
    public const FACADE_USER = 'FACADE_USER';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);
        $container = $this->addWarehouseUserFacade($container);
        $container = $this->addUserFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addWarehouseUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_WAREHOUSE_USER, function (Container $container) {
            return new WarehouseUsersBackendApiToWarehouseUserFacadeBridge(
                $container->getLocator()->warehouseUser()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER, function (Container $container) {
            return new WarehouseUsersBackendApiToUserFacadeBridge($container->getLocator()->user()->facade());
        });

        return $container;
    }
}
