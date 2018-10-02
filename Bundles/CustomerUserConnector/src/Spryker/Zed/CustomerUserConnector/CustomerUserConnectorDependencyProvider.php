<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnector;

use Spryker\Zed\CustomerUserConnector\Dependency\QueryContainer\CustomerUserConnectorToCustomerQueryContainerBridge;
use Spryker\Zed\CustomerUserConnector\Dependency\QueryContainer\CustomerUserConnectorToUserQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CustomerUserConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_USER = 'QUERY_CONTAINER_USER';
    public const QUERY_CONTAINER_CUSTOMER = 'QUERY_CONTAINER_CUSTOMER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_CUSTOMER] = function (Container $container) {
            return new CustomerUserConnectorToCustomerQueryContainerBridge($container->getLocator()->customer()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_USER] = function (Container $container) {
            return new CustomerUserConnectorToUserQueryContainerBridge($container->getLocator()->user()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addCustomerQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addUserQueryContainer($container);

        return $container;
    }
}
