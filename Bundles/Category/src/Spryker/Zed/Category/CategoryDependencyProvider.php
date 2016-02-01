<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Category;

use Spryker\Zed\Propel\Communication\Plugin\Connection;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleBridge;
use Spryker\Zed\Category\Dependency\Facade\CategoryToTouchBridge;
use Spryker\Zed\Category\Dependency\Facade\CategoryToUrlBridge;

class CategoryDependencyProvider extends AbstractBundleDependencyProvider
{

    const CATEGORY_QUERY_CONTAINER = 'category query container';
    const FACADE_TOUCH = 'touch facade';
    const FACADE_LOCALE = 'locale facade';
    const FACADE_URL = 'url facade';
    const PLUGIN_PROPEL_CONNECTION = 'propel connection plugin';

    /**
     * @var Container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new CategoryToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new CategoryToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_URL] = function (Container $container) {
            return new CategoryToUrlBridge($container->getLocator()->url()->facade());
        };

        $container[self::PLUGIN_PROPEL_CONNECTION] = function (Container $container) {
            return (new Connection())->get();
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new CategoryToLocaleBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

}
