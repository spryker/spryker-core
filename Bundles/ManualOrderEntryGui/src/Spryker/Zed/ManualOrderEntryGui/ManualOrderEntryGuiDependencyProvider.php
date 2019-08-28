<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCalculationFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCartFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCheckoutFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCurrencyFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCustomerFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToDiscountFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToManualOrderEntryFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMessengerFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMoneyFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToPaymentFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToProductFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToShipmentFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToStoreFacadeBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToCustomerQueryContainerBridge;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Service\ManualOrderEntryGuiToShipmentServiceBridge;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\ManualOrderEntryGuiConfig getConfig()
 */
class ManualOrderEntryGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_CART = 'FACADE_CART';
    public const FACADE_DISCOUNT = 'FACADE_DISCOUNT';
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';
    public const FACADE_SHIPMENT = 'FACADE_SHIPMENT';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_PAYMENT = 'FACADE_PAYMENT';
    public const FACADE_CHECKOUT = 'FACADE_CHECKOUT';
    public const FACADE_CALCULATION = 'FACADE_CALCULATION';
    public const FACADE_MANUAL_ORDER_ENTRY = 'FACADE_MANUAL_ORDER_ENTRY';

    public const PAYMENT_SUB_FORMS = 'PAYMENT_SUB_FORMS';

    public const QUERY_CONTAINER_CUSTOMER = 'QUERY_CONTAINER_CUSTOMER';

    public const PLUGINS_MANUAL_ORDER_ENTRY_FORM = 'PLUGINS_MANUAL_ORDER_ENTRY_FORM';
    public const PLUGINS_QUOTE_EXPANDER = 'PLUGINS_QUOTE_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addProductFacade($container);
        $container = $this->addCartFacade($container);
        $container = $this->addDiscountFacade($container);
        $container = $this->addCurrencyFacade($container);
        $container = $this->addMessengerFacade($container);
        $container = $this->addShipmentFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addPaymentFacade($container);
        $container = $this->addCheckoutFacade($container);
        $container = $this->addCalculationFacade($container);
        $container = $this->addManualOrderEntryFacade($container);
        $container = $this->addPaymentSubFormPlugins($container);
        $container = $this->addCustomerQueryContainer($container);
        $container = $this->addManualOrderEntryFormPlugins($container);
        $container = $this->addQuoteExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container): Container
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
    protected function addProductFacade(Container $container): Container
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
    protected function addCartFacade(Container $container): Container
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
    protected function addDiscountFacade(Container $container): Container
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
    protected function addCurrencyFacade(Container $container): Container
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
    protected function addMessengerFacade(Container $container): Container
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
    protected function addShipmentFacade(Container $container): Container
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
    protected function addStoreFacade(Container $container): Container
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new ManualOrderEntryGuiToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container): Container
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
    protected function addPaymentFacade(Container $container): Container
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
    protected function addCheckoutFacade(Container $container): Container
    {
        $container[static::FACADE_CHECKOUT] = function (Container $container) {
            return new ManualOrderEntryGuiToCheckoutFacadeBridge($container->getLocator()->checkout()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCalculationFacade(Container $container): Container
    {
        $container[static::FACADE_CALCULATION] = function (Container $container) {
            return new ManualOrderEntryGuiToCalculationFacadeBridge($container->getLocator()->calculation()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addManualOrderEntryFacade(Container $container): Container
    {
        $container[static::FACADE_MANUAL_ORDER_ENTRY] = function (Container $container) {
            return new ManualOrderEntryGuiToManualOrderEntryFacadeBridge($container->getLocator()->manualOrderEntry()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentSubFormPlugins(Container $container): Container
    {
        $container[static::PAYMENT_SUB_FORMS] = function () {
            return $this->getPaymentSubFormPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGuiExtension\Dependency\Plugin\PaymentSubFormPluginInterface[]
     */
    protected function getPaymentSubFormPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerQueryContainer(Container $container): Container
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
    protected function addManualOrderEntryFormPlugins(Container $container): Container
    {
        $container[static::PLUGINS_MANUAL_ORDER_ENTRY_FORM] = function (Container $container) {
            return $this->getManualOrderEntryFormPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[]
     */
    protected function getManualOrderEntryFormPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_QUOTE_EXPANDER] = function (Container $container) {
            return $this->getQuoteExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Plugin\QuoteExpanderPluginInterface[]
     */
    protected function getQuoteExpanderPlugins(): array
    {
        return [];
    }
}
