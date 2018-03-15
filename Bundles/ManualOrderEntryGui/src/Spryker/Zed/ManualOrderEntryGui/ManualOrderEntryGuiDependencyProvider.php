<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\AddressFormPlugin;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\CustomersListFormPlugin;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ItemFormPlugin;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ProductFormPlugin;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCartFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCustomerFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToProductFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToCustomerQueryContainerBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Service\ManualOrderEntryGuiToStoreBridge;

class ManualOrderEntryGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_CUSTOMER = 'MANUAL_ORDER_ENTRY_GUI:FACADE_CUSTOMER';
    const FACADE_PRODUCT = 'MANUAL_ORDER_ENTRY_GUI:FACADE_PRODUCT';
    const FACADE_CART = 'MANUAL_ORDER_ENTRY_GUI:FACADE_CART';

    const QUERY_CONTAINER_CUSTOMER = 'MANUAL_ORDER_ENTRY_GUI:QUERY_CONTAINER_CUSTOMER';

    const STORE = 'MANUAL_ORDER_ENTRY_GUI:STORE';

    const PLUGINS_MANUAL_ORDER_ENTRY_FORM = 'MANUAL_ORDER_ENTRY_GUI:PLUGINS_MANUAL_ORDER_ENTRY_FORM';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addProductFacade($container);
        $container = $this->addCartFacade($container);
        $container = $this->addCustomerQueryContainer($container);
        $container = $this->addStore($container);
        $container = $this->addManualOrderEntryFormPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container)
    {
        $container[static::FACADE_CUSTOMER] = function (Container $container) {
            return new ManualOrderEntryGuiToCustomerFacadeBridge($container->getLocator()->customer()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new ManualOrderEntryGuiToProductFacadeBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartFacade(Container $container)
    {
        $container[static::FACADE_CART] = function (Container $container) {
            return new ManualOrderEntryGuiToCartFacadeBridge($container->getLocator()->cart()->facade());
        };

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
            return new ManualOrderEntryGuiToCustomerQueryContainerBridge($container->getLocator()->customer()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function () {
            return new ManualOrderEntryGuiToStoreBridge(Store::getInstance());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addManualOrderEntryFormPlugins(Container $container)
    {
        $container[static::PLUGINS_MANUAL_ORDER_ENTRY_FORM] = function (Container $container) {
            return $this->getManualOrderEntryFormPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[]
     */
    protected function getManualOrderEntryFormPlugins(Container $container)
    {
        return [
            new ProductFormPlugin(
                $container[static::FACADE_CART],
                $container[static::FACADE_PRODUCT]
            ),
            new ItemFormPlugin($container[static::FACADE_CART]),
            new CustomersListFormPlugin($container[static::FACADE_CUSTOMER]),
            new AddressFormPlugin(),
        ];
    }

}
