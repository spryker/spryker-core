<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToAvailabilityBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToCategoryBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToCurrencyBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToMoneyBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStockBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToTaxBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToTouchBridge;
use Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilEncodingBridge;
use Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilTextBridge;

class ProductManagementDependencyProvider extends AbstractBundleDependencyProvider
{

    const STORE = 'STORE';

    const FACADE_CATEGORY = 'FACADE_LOCALE';
    const FACADE_LOCALE = 'FACADE_LOCALE';
    const FACADE_PRODUCT = 'FACADE_PRODUCT';
    const FACADE_PRODUCT_IMAGE = 'FACADE_PRODUCT_IMAGE';
    const FACADE_TOUCH = 'FACADE_TOUCH';
    const FACADE_TAX = 'FACADE_TAX';
    const FACADE_PRICE = 'FACADE_PRICE';
    const FACADE_GLOSSARY = 'FACADE_GLOSSARY';
    const FACADE_STOCK = 'FACADE_STOCK';
    const FACADE_MONEY = 'FACADE_MONEY';
    const FACADE_CURRENCY = 'FACADE_CURRENCY';
    const FACADE_AVAILABILITY = 'FACADE_AVAILABILITY';

    const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';
    const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';
    const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';
    const QUERY_CONTAINER_STOCK = 'QUERY_CONTAINER_STOCK';
    const QUERY_CONTAINER_PRODUCT_IMAGE = 'QUERY_CONTAINER_PRODUCT_IMAGE';
    const QUERY_CONTAINER_PRODUCT_GROUP = 'QUERY_CONTAINER_PRODUCT_GROUP';

    const PLUGINS_PRODUCT_ABSTRACT_VIEW = 'PRODUCT_MANAGEMENT:PLUGINS_PRODUCT_ABSTRACT_VIEW';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new ProductManagementToProductBridge($container->getLocator()->product()->facade());
        };

        $container[self::FACADE_CATEGORY] = function (Container $container) {
            return new ProductManagementToCategoryBridge($container->getLocator()->category()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductManagementToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new ProductManagementToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::SERVICE_UTIL_TEXT] = function (Container $container) {
            return new ProductManagementToUtilTextBridge($container->getLocator()->utilText()->service());
        };

        $container[self::FACADE_TAX] = function (Container $container) {
            return new ProductManagementToTaxBridge($container->getLocator()->tax()->facade());
        };

        $container[self::FACADE_PRODUCT_IMAGE] = function (Container $container) {
            return new ProductManagementToProductImageBridge($container->getLocator()->productImage()->facade());
        };

        $container[self::FACADE_PRICE] = function (Container $container) {
            return new ProductManagementToPriceBridge($container->getLocator()->price()->facade());
        };

        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new ProductManagementToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        $container[self::FACADE_STOCK] = function (Container $container) {
            return new ProductManagementToStockBridge($container->getLocator()->stock()->facade());
        };

        $container[self::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_STOCK] = function (Container $container) {
            return $container->getLocator()->stock()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_PRODUCT_IMAGE] = function (Container $container) {
            return $container->getLocator()->productImage()->queryContainer();
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
        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new ProductManagementToProductBridge($container->getLocator()->product()->facade());
        };

        $container[self::FACADE_CATEGORY] = function (Container $container) {
            return new ProductManagementToCategoryBridge($container->getLocator()->category()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductManagementToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new ProductManagementToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::SERVICE_UTIL_TEXT] = function (Container $container) {
            return new ProductManagementToUtilTextBridge($container->getLocator()->utilText()->service());
        };

        $container[self::FACADE_TAX] = function (Container $container) {
            return new ProductManagementToTaxBridge($container->getLocator()->tax()->facade());
        };

        $container[self::FACADE_PRICE] = function (Container $container) {
            return new ProductManagementToPriceBridge($container->getLocator()->price()->facade());
        };

        $container[self::FACADE_PRODUCT_IMAGE] = function (Container $container) {
            return new ProductManagementToProductImageBridge($container->getLocator()->productImage()->facade());
        };

        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new ProductManagementToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        $container[self::FACADE_STOCK] = function (Container $container) {
            return new ProductManagementToStockBridge($container->getLocator()->stock()->facade());
        };

        $container[self::FACADE_MONEY] = function (Container $container) {
            return new ProductManagementToMoneyBridge($container->getLocator()->money()->facade());
        };

        $container[self::FACADE_CURRENCY] = function (Container $container) {
            return new ProductManagementToCurrencyBridge($container->getLocator()->currency()->facade());
        };

        $container[self::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_STOCK] = function (Container $container) {
            return $container->getLocator()->stock()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_PRODUCT_IMAGE] = function (Container $container) {
            return $container->getLocator()->productImage()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_PRODUCT_GROUP] = function (Container $container) {
            return $container->getLocator()->productGroup()->queryContainer();
        };

        $container[self::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new ProductManagementToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        $container[self::FACADE_AVAILABILITY] = function (Container $container) {
            return new ProductManagementToAvailabilityBridge($container->getLocator()->availability()->facade());
        };

        $container = $this->addStore($container);
        $container = $this->addProductAbstractViewPlugins($container);

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
     * @return \Spryker\Zed\ProductManagement\Communication\Plugin\ProductAbstractViewPluginInterface[]
     */
    protected function getProductAbstractViewPlugins()
    {
        return [];
    }

}
