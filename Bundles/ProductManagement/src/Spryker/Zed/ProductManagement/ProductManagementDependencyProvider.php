<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToAvailabilityBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToCategoryBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToCurrencyBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToMoneyBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductAttributeBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductBundleBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStockBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToTaxBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToTouchBridge;
use Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilEncodingBridge;
use Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilTextBridge;
use Spryker\Zed\ProductManagement\Exception\MissingMoneyTypePluginException;
use Spryker\Zed\ProductManagement\Exception\MissingStoreRelationFormTypePluginException;

class ProductManagementDependencyProvider extends AbstractBundleDependencyProvider
{
    public const STORE = 'STORE';

    public const FACADE_CATEGORY = 'FACADE_LOCALE';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_PRODUCT_ATTRIBUTE = 'FACADE_PRODUCT_ATTRIBUTE';
    public const FACADE_PRODUCT_IMAGE = 'FACADE_PRODUCT_IMAGE';
    public const FACADE_PRODUCT_BUNDLE = 'FACADE_PRODUCT_BUNDLE';
    public const FACADE_TOUCH = 'FACADE_TOUCH';
    public const FACADE_TAX = 'FACADE_TAX';
    public const FACADE_PRICE = 'FACADE_PRICE';
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';
    public const FACADE_STOCK = 'FACADE_STOCK';
    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';
    public const FACADE_AVAILABILITY = 'FACADE_AVAILABILITY';

    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    public const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';
    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';
    public const QUERY_CONTAINER_STOCK = 'QUERY_CONTAINER_STOCK';
    public const QUERY_CONTAINER_PRODUCT_IMAGE = 'QUERY_CONTAINER_PRODUCT_IMAGE';
    public const QUERY_CONTAINER_PRODUCT_GROUP = 'QUERY_CONTAINER_PRODUCT_GROUP';

    public const PLUGINS_PRODUCT_ABSTRACT_VIEW = 'PRODUCT_MANAGEMENT:PLUGINS_PRODUCT_ABSTRACT_VIEW';
    public const PLUGIN_STORE_RELATION_FORM_TYPE = 'PLUGIN_STORE_RELATION_FORM_TYPE';

    public const PLUGIN_MONEY_FORM_TYPE = 'MONEY_FORM_TYPE_PLUGIN';

    public const PRODUCT_CONCRETE_EDIT_FORM_EXPANDER_PLUGINS = 'PRODUCT_CONCRETE_EDIT_FORM_EXPANDER_PLUGINS';
    public const PRODUCT_CONCRETE_FORM_EDIT_DATA_PROVIDER_EXPANDER_PLUGINS = 'PRODUCT_CONCRETE_FORM_EDIT_DATA_PROVIDER_EXPANDER_PLUGINS';
    public const PRODUCT_FORM_TRANSFER_MAPPER_EXPANDER_PLUGINS = 'PRODUCT_FORM_TRANSFER_MAPPER_EXPANDER_PLUGINS';
    public const PLUGINS_PRODUCT_CONCRETE_FORM_EDIT_TABS_EXPANDER = 'PLUGINS_PRODUCT_CONCRETE_FORM_EDIT_TABS_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new ProductManagementToProductBridge($container->getLocator()->product()->facade());
        };

        $container[static::FACADE_CATEGORY] = function (Container $container) {
            return new ProductManagementToCategoryBridge($container->getLocator()->category()->facade());
        };

        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ProductManagementToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[static::FACADE_TOUCH] = function (Container $container) {
            return new ProductManagementToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[static::SERVICE_UTIL_TEXT] = function (Container $container) {
            return new ProductManagementToUtilTextBridge($container->getLocator()->utilText()->service());
        };

        $container[static::FACADE_TAX] = function (Container $container) {
            return new ProductManagementToTaxBridge($container->getLocator()->tax()->facade());
        };

        $container[static::FACADE_PRODUCT_IMAGE] = function (Container $container) {
            return new ProductManagementToProductImageBridge($container->getLocator()->productImage()->facade());
        };

        $container[static::FACADE_PRICE_PRODUCT] = function (Container $container) {
            return new ProductManagementToPriceProductBridge($container->getLocator()->priceProduct()->facade());
        };

        $container = $this->addStockFacade($container);

        $container[static::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        };

        $container[static::FACADE_PRODUCT_ATTRIBUTE] = function (Container $container) {
            return new ProductManagementToProductAttributeBridge($container->getLocator()->productAttribute()->facade());
        };

        $container[static::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->queryContainer();
        };

        $container[static::QUERY_CONTAINER_STOCK] = function (Container $container) {
            return $container->getLocator()->stock()->queryContainer();
        };

        $container[static::QUERY_CONTAINER_PRODUCT_IMAGE] = function (Container $container) {
            return $container->getLocator()->productImage()->queryContainer();
        };

        $container[static::FACADE_PRICE] = function (Container $container) {
            return new ProductManagementToPriceBridge($container->getLocator()->price()->facade());
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
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new ProductManagementToProductBridge($container->getLocator()->product()->facade());
        };

        $container = $this->addProductBundleFacade($container);

        $container[static::FACADE_CATEGORY] = function (Container $container) {
            return new ProductManagementToCategoryBridge($container->getLocator()->category()->facade());
        };

        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ProductManagementToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[static::FACADE_TOUCH] = function (Container $container) {
            return new ProductManagementToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[static::SERVICE_UTIL_TEXT] = function (Container $container) {
            return new ProductManagementToUtilTextBridge($container->getLocator()->utilText()->service());
        };

        $container[static::FACADE_TAX] = function (Container $container) {
            return new ProductManagementToTaxBridge($container->getLocator()->tax()->facade());
        };

        $container[static::FACADE_PRICE_PRODUCT] = function (Container $container) {
            return new ProductManagementToPriceProductBridge($container->getLocator()->priceProduct()->facade());
        };

        $container[static::FACADE_PRODUCT_IMAGE] = function (Container $container) {
            return new ProductManagementToProductImageBridge($container->getLocator()->productImage()->facade());
        };

        $container = $this->addStockFacade($container);

        $container[static::FACADE_MONEY] = function (Container $container) {
            return new ProductManagementToMoneyBridge($container->getLocator()->money()->facade());
        };

        $container[static::FACADE_CURRENCY] = function (Container $container) {
            return new ProductManagementToCurrencyBridge($container->getLocator()->currency()->facade());
        };

        $container[static::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        };

        $container[static::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->queryContainer();
        };

        $container[static::QUERY_CONTAINER_STOCK] = function (Container $container) {
            return $container->getLocator()->stock()->queryContainer();
        };

        $container[static::QUERY_CONTAINER_PRODUCT_IMAGE] = function (Container $container) {
            return $container->getLocator()->productImage()->queryContainer();
        };

        $container[static::QUERY_CONTAINER_PRODUCT_GROUP] = function (Container $container) {
            return $container->getLocator()->productGroup()->queryContainer();
        };

        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new ProductManagementToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        $container[static::FACADE_AVAILABILITY] = function (Container $container) {
            return new ProductManagementToAvailabilityBridge($container->getLocator()->availability()->facade());
        };

        $container[static::FACADE_PRODUCT_ATTRIBUTE] = function (Container $container) {
            return new ProductManagementToProductAttributeBridge($container->getLocator()->productAttribute()->facade());
        };

        $container[static::FACADE_PRICE] = function (Container $container) {
            return new ProductManagementToPriceBridge($container->getLocator()->price()->facade());
        };

        $container = $this->addStockFacade($container);
        $container = $this->addStore($container);
        $container = $this->addProductAbstractViewPlugins($container);
        $container = $this->addStoreRelationFormTypePlugin($container);
        $container = $this->addMoneyFormTypePlugin($container);
        $container = $this->addProductConcreteEditFormExpanderPlugins($container);
        $container = $this->addProductConcreteFormEditDataProviderExpanderPlugins($container);
        $container = $this->addProductFormTransferMapperExpanderPlugins($container);
        $container = $this->addProductConcreteFormEditTabsExpanderPlugins($container);

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
            return new ProductManagementToStoreBridge(Store::getInstance());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractViewPlugins(Container $container)
    {
        $container[static::PLUGINS_PRODUCT_ABSTRACT_VIEW] = function () {
            return $this->getProductAbstractViewPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFormTypePlugin(Container $container)
    {
        $container[static::PLUGIN_MONEY_FORM_TYPE] = function (Container $container) {
            return $this->createMoneyFormTypePlugin($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @throws \Spryker\Zed\ProductManagement\Exception\MissingMoneyTypePluginException
     *
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    protected function createMoneyFormTypePlugin(Container $container)
    {
        throw new MissingMoneyTypePluginException(
            sprintf(
                'Missing instance of %s! You need to configure MoneyFormTypePlugin ' .
                'in your own ProductManagementDependencyProvider::createMoneyFormTypePlugin() ' .
                'to be able to manage product prices.',
                FormTypeInterface::class
            )
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Plugin\ProductAbstractViewPluginInterface[]
     */
    protected function getProductAbstractViewPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreRelationFormTypePlugin(Container $container)
    {
        $container[static::PLUGIN_STORE_RELATION_FORM_TYPE] = function () {
            return $this->getStoreRelationFormTypePlugin();
        };

        return $container;
    }

    /**
     * @throws \Spryker\Zed\ProductManagement\Exception\MissingStoreRelationFormTypePluginException
     *
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    protected function getStoreRelationFormTypePlugin()
    {
        throw new MissingStoreRelationFormTypePluginException(
            sprintf(
                'Missing instance of %s! You need to configure StoreRelationFormType ' .
                'in your own ProductManagementDependencyProvider::getStoreRelationFormTypePlugin() ' .
                'to be able to manage shipment prices.',
                FormTypeInterface::class
            )
        );
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductBundleFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT_BUNDLE] = function (Container $container) {
            return new ProductManagementToProductBundleBridge($container->getLocator()->productBundle()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStockFacade(Container $container)
    {
        $container[static::FACADE_STOCK] = function (Container $container) {
            return new ProductManagementToStockBridge($container->getLocator()->stock()->facade());
        };
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteEditFormExpanderPlugins(Container $container): Container
    {
        $container[static::PRODUCT_CONCRETE_EDIT_FORM_EXPANDER_PLUGINS] = function (Container $container) {
            return $this->getProductConcreteEditFormExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteEditFormExpanderPluginInterface[]
     */
    protected function getProductConcreteEditFormExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteFormEditDataProviderExpanderPlugins(Container $container): Container
    {
        $container[static::PRODUCT_CONCRETE_FORM_EDIT_DATA_PROVIDER_EXPANDER_PLUGINS] = function (Container $container) {
            return $this->getProductConcreteFormEditDataProviderExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditDataProviderExpanderPluginInterface[]
     */
    protected function getProductConcreteFormEditDataProviderExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFormTransferMapperExpanderPlugins(Container $container): Container
    {
        $container[static::PRODUCT_FORM_TRANSFER_MAPPER_EXPANDER_PLUGINS] = function (Container $container) {
            return $this->getProductFormTransferMapperExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditDataProviderExpanderPluginInterface[]
     */
    protected function getProductFormTransferMapperExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteFormEditTabsExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_PRODUCT_CONCRETE_FORM_EDIT_TABS_EXPANDER] = function (Container $container) {
            return $this->getProductConcreteFormEditTabsExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditTabsExpanderPluginInterface[]
     */
    protected function getProductConcreteFormEditTabsExpanderPlugins(): array
    {
        return [];
    }
}
