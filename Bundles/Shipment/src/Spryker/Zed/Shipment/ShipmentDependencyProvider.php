<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCalculationFacadeBridge;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyBridge;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyBridge;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToPriceFacadeBridge;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeBridge;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreBridge;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxBridge;
use Spryker\Zed\Shipment\Exception\MissingMoneyCollectionFormTypePluginException;

/**
 * @method \Spryker\Zed\Shipment\ShipmentConfig getConfig()
 */
class ShipmentDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @deprecated Will be removed in the next major version.
     */
    public const PLUGINS = 'PLUGINS';
    public const AVAILABILITY_PLUGINS = 'AVAILABILITY_PLUGINS';
    public const PRICE_PLUGINS = 'PRICE_PLUGINS';
    public const DELIVERY_TIME_PLUGINS = 'DELIVERY_TIME_PLUGINS';
    public const MONEY_COLLECTION_FORM_TYPE_PLUGIN = 'MONEY_COLLECTION_FORM_TYPE_PLUGIN';

    public const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';

    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';
    public const FACADE_SALES = 'FACADE_SALES';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_TAX = 'FACADE_TAX';
    public const FACADE_PRICE = 'FACADE_PRICE';
    public const FACADE_CALCULATION = 'FACADE_CALCULATION';

    public const SHIPMENT_METHOD_FILTER_PLUGINS = 'SHIPMENT_METHOD_FILTER_PLUGINS';
    public const SHIPMENT_GROUPS_SANITIZER_PLUGINS = 'SHIPMENT_GROUPS_SANITIZER_PLUGINS';

    public const SERVICE_SHIPMENT = 'SERVICE_SHIPMENT';

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
        $container = $this->addSalesFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addCurrencyFacade($container);
        $container = $this->addMoneyCollectionFormTypePlugin($container);
        $container = $this->addShipmentService($container);

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
    protected function addAvailabilityPlugins(Container $container): Container
    {
        $container->set(static::AVAILABILITY_PLUGINS, function (Container $container) {
            return $this->getAvailabilityPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPricePlugins(Container $container): Container
    {
        $container->set(static::PRICE_PLUGINS, function (Container $container) {
            return $this->getPricePlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDeliveryTimePlugins(Container $container): Container
    {
        $container->set(static::DELIVERY_TIME_PLUGINS, function (Container $container) {
            return $this->getDeliveryTimePlugins($container);
        });

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
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container) {
            return new ShipmentToSalesFacadeBridge($container->getLocator()->sales()->facade());
        });

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
    protected function addPriceFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRICE, function (Container $container) {
            return new ShipmentToPriceFacadeBridge($container->getLocator()->price()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCalculationFacade(Container $container): Container
    {
        $container->set(static::FACADE_CALCULATION, function (Container $container) {
            return new ShipmentToCalculationFacadeBridge($container->getLocator()->calculation()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentService(Container $container): Container
    {
        $container->set(static::SERVICE_SHIPMENT, function (Container $container) {
            return $container->getLocator()->shipment()->service();
        });

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
        $container = $this->addSalesFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addMethodFilterPlugins($container);
        $container = $this->addShipmentService($container);
        $container = $this->addAvailabilityPlugins($container);
        $container = $this->addPricePlugins($container);
        $container = $this->addDeliveryTimePlugins($container);
        $container = $this->addShipmentGroupsSanitizerPlugins($container);
        $container = $this->addPriceFacade($container);
        $container = $this->addCalculationFacade($container);

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
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentGroupsSanitizerPlugins(Container $container)
    {
        $container->set(static::SHIPMENT_GROUPS_SANITIZER_PLUGINS, function () {
            return $this->getShipmentGroupsSanitizerPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodAvailabilityPluginInterface[]|\Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface[]
     */
    protected function getAvailabilityPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodPricePluginInterface[]|\Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodPricePluginInterface[]
     */
    protected function getPricePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodDeliveryTimePluginInterface[]|\Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodDeliveryTimePluginInterface[]
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
     * @return \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodFilterPluginInterface[]
     */
    protected function getMethodFilterPlugins(Container $container)
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentGroupsSanitizerPluginInterface[]
     */
    protected function getShipmentGroupsSanitizerPlugins()
    {
        return [];
    }
}
