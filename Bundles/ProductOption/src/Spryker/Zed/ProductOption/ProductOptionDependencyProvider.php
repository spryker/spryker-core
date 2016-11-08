<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToMoneyBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchBridge;
use Spryker\Zed\ProductOption\Dependency\QueryContainer\ProductOptionToSalesBridge;

class ProductOptionDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_LOCALE = 'FACADE_LOCALE';
    const FACADE_TAX = 'FACADE_TAX';
    const FACADE_TOUCH = 'FACADE_TOUCH';
    const FACADE_MONEY = 'FACADE_MONEY';

    const FACADE_GLOSSARY = 'FACADE_GLOSSARY';

    const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';


    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductOptionToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new ProductOptionToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new ProductOptionToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        $container[self::FACADE_TAX] = function (Container $container) {
            return new ProductOptionToTaxBridge($container->getLocator()->tax()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[self::QUERY_CONTAINER_SALES] = function (Container $container) {
            return new ProductOptionToSalesBridge($container->getLocator()->sales()->queryContainer());
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
        $container[self::FACADE_TAX] = function (Container $container) {
            return new ProductOptionToTaxBridge($container->getLocator()->tax()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductOptionToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_MONEY] = function (Container $container) {
            return new ProductOptionToMoneyBridge($container->getLocator()->money()->facade());
        };

        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new ProductOptionToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        return $container;
    }

}
