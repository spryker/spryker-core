<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderCreationGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ManualOrderCreationGui\Communication\Plugin\CheckoutCustomerFormPlugin;
use Spryker\Zed\ManualOrderCreationGui\Dependency\QueryContainer\ManualOrderCreationGuiToCustomerQueryContainerBridge;

class ManualOrderCreationGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    const QUERY_CONTAINER_CUSTOMER = 'MANUAL_ORDER_CREATION_GUI:QUERY_CONTAINER_CUSTOMER';

    const PLUGINS_CHECKOUT_FORM = 'MANUAL_ORDER_CREATION_GUI:PLUGINS_CHECKOUT_FORM';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCustomerQueryContainer($container);
        $container = $this->addCheckoutFormPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_CUSTOMER] = function (Container $container) {
            return new ManualOrderCreationGuiToCustomerQueryContainerBridge($container->getLocator()->customer()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCheckoutFormPlugins(Container $container)
    {
        $container[static::PLUGINS_CHECKOUT_FORM] = function (Container $container) {
            return $this->getCheckoutFormPlugins();
        };

        return $container;
    }

    /**
     * @return array
     */
    protected function getCheckoutFormPlugins()
    {
        $plugins = [
            new CheckoutCustomerFormPlugin(),
        ];

        return $plugins;
    }

}
