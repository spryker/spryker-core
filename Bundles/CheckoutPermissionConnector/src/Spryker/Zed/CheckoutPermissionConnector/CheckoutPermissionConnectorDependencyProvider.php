<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutPermissionConnector;

use Spryker\Zed\CheckoutPermissionConnector\Dependency\Facade\CheckoutPermissionConnectorToCheckoutPermissionFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CheckoutPermissionConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PERMISSION = 'FACADE_PERMISSION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addPermissionFacade($container);

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
            return new CheckoutPermissionConnectorToCheckoutPermissionFacadeBridge($container->getLocator()->permission()->facade());
        };

        return $container;
    }
}
