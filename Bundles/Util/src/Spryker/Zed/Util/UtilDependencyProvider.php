<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Util;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Util\Dependency\Facade\UtilToCategoryBridge;
use Spryker\Zed\Util\Dependency\Facade\UtilToGlossaryBridge;
use Spryker\Zed\Util\Dependency\Facade\UtilToLocaleBridge;
use Spryker\Zed\Util\Dependency\Facade\UtilToMoneyBridge;
use Spryker\Zed\Util\Dependency\Facade\UtilToPriceBridge;
use Spryker\Zed\Util\Dependency\Facade\UtilToProductBridge;
use Spryker\Zed\Util\Dependency\Facade\UtilToProductImageBridge;
use Spryker\Zed\Util\Dependency\Facade\UtilToStockBridge;
use Spryker\Zed\Util\Dependency\Facade\UtilToTaxBridge;
use Spryker\Zed\Util\Dependency\Facade\UtilToTouchBridge;
use Spryker\Zed\Util\Dependency\Facade\UtilToUrlBridge;

class UtilDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_CATEGORY = 'FACADE_LOCALE';
    const FACADE_LOCALE = 'FACADE_LOCALE';
    const FACADE_PRODUCT = 'FACADE_PRODUCT';
    const FACADE_PRODUCT_IMAGE = 'FACADE_PRODUCT_IMAGE';
    const FACADE_TOUCH = 'FACADE_TOUCH';
    const FACADE_URL = 'FACADE_URL';
    const FACADE_TAX = 'FACADE_TAX';
    const FACADE_PRICE = 'FACADE_PRICE';
    const FACADE_GLOSSARY = 'FACADE_GLOSSARY';
    const FACADE_STOCK = 'FACADE_STOCK';
    const FACADE_MONEY = 'FACADE_MONEY';

    const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';
    const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';
    const QUERY_CONTAINER_STOCK = 'QUERY_CONTAINER_STOCK';
    const QUERY_CONTAINER_PRODUCT_IMAGE = 'QUERY_CONTAINER_PRODUCT_IMAGE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new UtilToProductBridge($container->getLocator()->product()->facade());
        };

        $container[self::FACADE_CATEGORY] = function (Container $container) {
            return new UtilToCategoryBridge($container->getLocator()->category()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new UtilToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new UtilToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_URL] = function (Container $container) {
            return new UtilToUrlBridge($container->getLocator()->url()->facade());
        };

        $container[self::FACADE_TAX] = function (Container $container) {
            return new UtilToTaxBridge($container->getLocator()->tax()->facade());
        };

        $container[self::FACADE_PRODUCT_IMAGE] = function (Container $container) {
            return new UtilToProductImageBridge($container->getLocator()->productImage()->facade());
        };

        $container[self::FACADE_PRICE] = function (Container $container) {
            return new UtilToPriceBridge($container->getLocator()->price()->facade());
        };

        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new UtilToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        $container[self::FACADE_STOCK] = function (Container $container) {
            return new UtilToStockBridge($container->getLocator()->stock()->facade());
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
            return new UtilToProductBridge($container->getLocator()->product()->facade());
        };

        $container[self::FACADE_CATEGORY] = function (Container $container) {
            return new UtilToCategoryBridge($container->getLocator()->category()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new UtilToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new UtilToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_URL] = function (Container $container) {
            return new UtilToUrlBridge($container->getLocator()->url()->facade());
        };

        $container[self::FACADE_TAX] = function (Container $container) {
            return new UtilToTaxBridge($container->getLocator()->tax()->facade());
        };

        $container[self::FACADE_PRICE] = function (Container $container) {
            return new UtilToPriceBridge($container->getLocator()->price()->facade());
        };

        $container[self::FACADE_PRODUCT_IMAGE] = function (Container $container) {
            return new UtilToProductImageBridge($container->getLocator()->productImage()->facade());
        };

        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new UtilToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        $container[self::FACADE_STOCK] = function (Container $container) {
            return new UtilToStockBridge($container->getLocator()->stock()->facade());
        };

        $container[self::FACADE_MONEY] = function (Container $container) {
            return new UtilToMoneyBridge($container->getLocator()->money()->facade());
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

}
