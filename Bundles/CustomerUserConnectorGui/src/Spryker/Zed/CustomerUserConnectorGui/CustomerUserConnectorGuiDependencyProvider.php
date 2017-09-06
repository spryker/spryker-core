<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui;

use Spryker\Zed\CustomerUserConnectorGui\Dependency\QueryContainer\CustomerUserConnectorGuiToCustomerQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CustomerUserConnectorGuiDependencyProvider extends AbstractBundleDependencyProvider
{

    const QUERY_CONTAINER_CUSTOMER = 'QUERY_CONTAINER_CUSTOMER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_CUSTOMER] = function (Container $container) {
            return new CustomerUserConnectorGuiToCustomerQueryContainerBridge($container->getLocator()->customer()->queryContainer());
        };

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

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCustomerQueryContainer($container);

        return $container;
    }

}
