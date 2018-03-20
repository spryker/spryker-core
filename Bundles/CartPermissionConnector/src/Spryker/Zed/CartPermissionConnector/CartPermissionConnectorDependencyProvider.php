<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartPermissionConnector;

use Spryker\Zed\CartPermissionConnector\Dependency\Facade\CartPermissionConnectorToMessengerFacadeBridge;
use Spryker\Zed\CartPermissionConnector\Dependency\Facade\CartPermissionConnectorToPermissionFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CartPermissionConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PERMISSION = 'FACADE_PERMISSION';
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addPermissionFacade($container);
        $container = $this->addMessengerFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPermissionFacade(Container $container)
    {
        $container[static::FACADE_PERMISSION] = function (Container $container) {
            return new CartPermissionConnectorToPermissionFacadeBridge($container->getLocator()->permission()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container)
    {
        $container[static::FACADE_MESSENGER] = function (Container $container) {
            return new CartPermissionConnectorToMessengerFacadeBridge($container->getLocator()->messenger()->facade());
        };

        return $container;
    }
}
