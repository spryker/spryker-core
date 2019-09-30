<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyBridge;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyBridge;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreBridge;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxBridge;
use Spryker\Zed\Shipment\Exception\MissingMoneyCollectionFormTypePluginException;

/**
 * @method \Spryker\Zed\Shipment\ShipmentConfig getConfig()
 */
class ShipmentDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS = 'PLUGINS';
    public const AVAILABILITY_PLUGINS = 'AVAILABILITY_PLUGINS';
    public const PRICE_PLUGINS = 'PRICE_PLUGINS';
    public const DELIVERY_TIME_PLUGINS = 'DELIVERY_TIME_PLUGINS';
    public const MONEY_COLLECTION_FORM_TYPE_PLUGIN = 'MONEY_COLLECTION_FORM_TYPE_PLUGIN';

    public const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';

    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_TAX = 'FACADE_TAX';
    public const SHIPMENT_METHOD_FILTER_PLUGINS = 'SHIPMENT_METHOD_FILTER_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::PLUGINS] = function (Container $container) {
            return [
                static::AVAILABILITY_PLUGINS => $this->getAvailabilityPlugins($container),
                static::PRICE_PLUGINS => $this->getPricePlugins($container),
                static::DELIVERY_TIME_PLUGINS => $this->getDeliveryTimePlugins($container),
            ];
        };

        $container = $this->addMoneyFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addCurrencyFacade($container);
        $container = $this->addMoneyCollectionFormTypePlugin($container);

        $container[static::FACADE_TAX] = function (Container $container) {
            return new ShipmentToTaxBridge($container->getLocator()->tax()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyCollectionFormTypePlugin(Container $container)
    {
        $container[static::MONEY_COLLECTION_FORM_TYPE_PLUGIN] = function (Container $container) {
            return $this->createMoneyCollectionFormTypePlugin($container);
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
            return new ShipmentToMoneyBridge($container->getLocator()->money()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container)
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new ShipmentToStoreBridge($container->getLocator()->store()->facade());
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
            return new ShipmentToCurrencyBridge($container->getLocator()->currency()->facade());
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
        $container[static::PLUGINS] = function (Container $container) {
            return [
                static::AVAILABILITY_PLUGINS => $this->getAvailabilityPlugins($container),
                static::PRICE_PLUGINS => $this->getPricePlugins($container),
                static::DELIVERY_TIME_PLUGINS => $this->getDeliveryTimePlugins($container),
            ];
        };

        $container[static::QUERY_CONTAINER_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->queryContainer();
        };

        $container[static::FACADE_TAX] = function (Container $container) {
            return new ShipmentToTaxBridge($container->getLocator()->tax()->facade());
        };

        $container = $this->addCurrencyFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addMethodFilterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMethodFilterPlugins(Container $container)
    {
        $container[static::SHIPMENT_METHOD_FILTER_PLUGINS] = function (Container $container) {
            return $this->getMethodFilterPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array
     */
    protected function getAvailabilityPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array
     */
    protected function getPricePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array
     */
    protected function getDeliveryTimePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @throws \Spryker\Zed\Shipment\Exception\MissingMoneyCollectionFormTypePluginException
     *
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    protected function createMoneyCollectionFormTypePlugin(Container $container)
    {
        throw new MissingMoneyCollectionFormTypePluginException(
            sprintf(
                'Missing instance of %s! You need to configure MoneyCollectionFormType ' .
                'in your own ShipmentDependencyProvider::createMoneyCollectionFormTypePlugin() ' .
                'to be able to manage shipment prices.',
                FormTypeInterface::class
            )
        );
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Shipment\Dependency\Plugin\ShipmentMethodFilterPluginInterface[]
     */
    protected function getMethodFilterPlugins(Container $container)
    {
        return [];
    }
}
