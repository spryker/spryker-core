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
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\Payment\SubFormPluginCollection;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\PaymentFormPlugin;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ProductFormPlugin;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ShipmentFormPlugin;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\StoreFormPlugin;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\VoucherFormPlugin;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCartFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCurrencyFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCustomerFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToDiscountFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMessengerFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMoneyFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToPaymentFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToProductFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToShipmentFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToCustomerQueryContainerBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Service\ManualOrderEntryGuiToStoreBridge;

class ManualOrderEntryGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_CUSTOMER = 'MANUAL_ORDER_ENTRY_GUI:FACADE_CUSTOMER';
    const FACADE_PRODUCT = 'MANUAL_ORDER_ENTRY_GUI:FACADE_PRODUCT';
    const FACADE_CART = 'MANUAL_ORDER_ENTRY_GUI:FACADE_CART';
    const FACADE_DISCOUNT = 'MANUAL_ORDER_ENTRY_GUI:FACADE_DISCOUNT';
    const FACADE_CURRENCY = 'MANUAL_ORDER_ENTRY_GUI:FACADE_CURRENCY';
    const FACADE_MESSENGER = 'MANUAL_ORDER_ENTRY_GUI:FACADE_MESSENGER';
    const FACADE_SHIPMENT = 'MANUAL_ORDER_ENTRY_GUI:FACADE_SHIPMENT';
    const FACADE_MONEY = 'MANUAL_ORDER_ENTRY_GUI:FACADE_MONEY';
    const FACADE_PAYMENT = 'MANUAL_ORDER_ENTRY_GUI:FACADE_PAYMENT';

    const PAYMENT_SUB_FORMS = 'PAYMENT SUB FORMS';

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
        $container = $this->addDiscountFacade($container);
        $container = $this->addCurrencyFacade($container);
        $container = $this->addMessengerFacade($container);
        $container = $this->addShipmentFacade($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addPaymentFacade($container);

        $container = $this->addPaymentSubFormPlugins($container);

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
    protected function addDiscountFacade(Container $container)
    {
        $container[static::FACADE_DISCOUNT] = function (Container $container) {
            return new ManualOrderEntryGuiToDiscountFacadeBridge($container->getLocator()->discount()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container)
    {
        $container[static::FACADE_CURRENCY] = function (Container $container) {
            return new ManualOrderEntryGuiToCurrencyFacadeBridge($container->getLocator()->currency()->facade());
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
            return new ManualOrderEntryGuiToMessengerFacadeBridge($container->getLocator()->messenger()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentFacade(Container $container)
    {
        $container[static::FACADE_SHIPMENT] = function (Container $container) {
            return new ManualOrderEntryGuiToShipmentFacadeBridge($container->getLocator()->shipment()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container)
    {
        $container[static::FACADE_MONEY] = function (Container $container) {
            return new ManualOrderEntryGuiToMoneyFacadeBridge($container->getLocator()->money()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentFacade(Container $container)
    {
        $container[static::FACADE_PAYMENT] = function (Container $container) {
            return new ManualOrderEntryGuiToPaymentFacadeBridge($container->getLocator()->payment()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentSubFormPlugins(Container $container)
    {
        $container[static::PAYMENT_SUB_FORMS] = function () {
            return $this->getPaymentSubFormPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\Payment\SubFormPluginInterface[]
     */
    protected function getPaymentSubFormPlugins()
    {
        return [];
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
            new CustomersListFormPlugin($container[static::FACADE_CUSTOMER]),
            new StoreFormPlugin($container[static::FACADE_CURRENCY]),
            new PaymentFormPlugin(),
            new ProductFormPlugin(
                $container[static::FACADE_CART],
                $container[static::FACADE_PRODUCT]
            ),
            new ItemFormPlugin($container[static::FACADE_CART]),
            new VoucherFormPlugin(
                $container[static::FACADE_DISCOUNT],
                $container[static::FACADE_MESSENGER]
            ),
            new AddressFormPlugin(),
            new ShipmentFormPlugin($container[static::FACADE_SHIPMENT]),
        ];
    }

}
