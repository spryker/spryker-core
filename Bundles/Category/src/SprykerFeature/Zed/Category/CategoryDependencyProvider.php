<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Category;

use Spryker\Zed\Propel\Communication\Plugin\Connection;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CategoryDependencyProvider extends AbstractBundleDependencyProvider
{

    const QUERY_CONTAINER_CATEGORY = 'category query container';
    const FACADE_TOUCH = 'touch facade';
    const FACADE_LOCALE = 'locale facade';
    const FACADE_URL = 'url facade';
    const FACADE_CATEGORY = 'category facade';
    const PLUGIN_PROPEL_CONNECTION = 'propel connection plugin';

    /**
     * @var Container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->facade();
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return $container->getLocator()->locale()->facade();
        };

        $container[self::FACADE_URL] = function (Container $container) {
            return $container->getLocator()->url()->facade();
        };

        $container[self::FACADE_CATEGORY] = function (Container $container) {
            return $container->getLocator()->category()->facade();
        };

        $container[self::PLUGIN_PROPEL_CONNECTION] = function (Container $container) {
            return (new Connection())->get();
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return $container->getLocator()->locale()->facade();
        };

        $container[self::FACADE_CATEGORY] = function (Container $container) {
            return $container->getLocator()->category()->facade();
        };

        $container[self::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        };

        return $container;
    }

}
