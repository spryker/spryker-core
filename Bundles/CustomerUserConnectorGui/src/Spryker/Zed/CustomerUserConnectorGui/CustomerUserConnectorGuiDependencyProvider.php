<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui;

use Spryker\Zed\CustomerUserConnectorGui\Dependency\Facade\CustomerUserConnectorGuiToCustomerUserConnectorBridge;
use Spryker\Zed\CustomerUserConnectorGui\Dependency\QueryContainer\CustomerUserConnectorGuiToCustomerQueryContainerBridge;
use Spryker\Zed\CustomerUserConnectorGui\Dependency\QueryContainer\CustomerUserConnectorGuiToUserQueryContainerBridge;
use Spryker\Zed\CustomerUserConnectorGui\Dependency\Service\CustomerUserConnectorGuiToUtilSanitizeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CustomerUserConnectorGui\CustomerUserConnectorGuiConfig getConfig()
 */
class CustomerUserConnectorGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_CUSTOMER = 'QUERY_CONTAINER_CUSTOMER';
    public const QUERY_CONTAINER_USER = 'QUERY_CONTAINER_USER';
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';

    public const FACADE_CUSTOMER_USER_CONNECTOR = 'FACADE_CUSTOMER_USER_CONNECTOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerQueryContainer(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_CUSTOMER, function (Container $container) {
            return new CustomerUserConnectorGuiToCustomerQueryContainerBridge($container->getLocator()->customer()->queryContainer());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserQueryContainer(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_USER, function (Container $container) {
            return new CustomerUserConnectorGuiToUserQueryContainerBridge($container->getLocator()->user()->queryContainer());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerUserConnectorFacade(Container $container)
    {
        $container->set(static::FACADE_CUSTOMER_USER_CONNECTOR, function (Container $container) {
            return new CustomerUserConnectorGuiToCustomerUserConnectorBridge($container->getLocator()->customerUserConnector()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addCustomerQueryContainer($container);
        $container = $this->addUserQueryContainer($container);
        $container = $this->addCustomerUserConnectorFacade($container);
        $container = $this->addUtilSanitizeService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilSanitizeService(Container $container)
    {
        $container->set(static::SERVICE_UTIL_SANITIZE, function (Container $container) {
            return new CustomerUserConnectorGuiToUtilSanitizeBridge($container->getLocator()->utilSanitize()->service());
        });

        return $container;
    }
}
