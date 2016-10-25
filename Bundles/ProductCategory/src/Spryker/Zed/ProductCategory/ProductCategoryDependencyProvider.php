<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryBridge;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCmsBridge;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToLocaleBridge;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToProductBridge;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToTouchBridge;
use Spryker\Zed\Propel\Communication\Plugin\Connection;

class ProductCategoryDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @deprecated Will be removed with next major release
     */
    const FACADE_CMS = 'cms facade'; //TODO: https://spryker.atlassian.net/browse/CD-540

    /**
     * @deprecated Will be removed with next major release
     */
    const FACADE_LOCALE = 'locale facade';

    /**
     * @deprecated Will be removed with next major release
     */
    const FACADE_PRODUCT = 'product facade';

    /**
     * @deprecated Will be removed with next major release
     */
    const FACADE_CATEGORY = 'category facade';

    /**
     * @deprecated Will be removed with next major release
     */
    const PRODUCT_QUERY_CONTAINER = 'product query container';

    const FACADE_TOUCH = 'touch facade';
    const CATEGORY_QUERY_CONTAINER = 'category query container';
    const PLUGIN_PROPEL_CONNECTION = 'propel connection plugin';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_CMS] = function (Container $container) {
            return new ProductCategoryToCmsBridge($container->getLocator()->cms()->facade());
        };

        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new ProductCategoryToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductCategoryToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new ProductCategoryToProductBridge($container->getLocator()->product()->facade());
        };

        $container[self::FACADE_CATEGORY] = function (Container $container) {
            return new ProductCategoryToCategoryBridge($container->getLocator()->category()->facade());
        };

        $container[self::CATEGORY_QUERY_CONTAINER] = function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        };

        $container[self::PRODUCT_QUERY_CONTAINER] = function (Container $container) {
            return $container->getLocator()->product()->queryContainer();
        };

        $container[self::PLUGIN_PROPEL_CONNECTION] = function () {
            return (new Connection())->get();
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
        $container[self::FACADE_CMS] = function (Container $container) {
            return new ProductCategoryToCmsBridge($container->getLocator()->cms()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductCategoryToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new ProductCategoryToProductBridge($container->getLocator()->product()->facade());
        };

        $container[self::FACADE_CATEGORY] = function (Container $container) {
            return new ProductCategoryToCategoryBridge($container->getLocator()->category()->facade());
        };

        $container[self::CATEGORY_QUERY_CONTAINER] = function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        };

        $container[self::PRODUCT_QUERY_CONTAINER] = function (Container $container) {
            return $container->getLocator()->product()->queryContainer();
        };

        $container[self::PLUGIN_PROPEL_CONNECTION] = function () {
            return (new Connection())->get();
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
        $container[self::CATEGORY_QUERY_CONTAINER] = function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        };

        return $container;
    }

}
