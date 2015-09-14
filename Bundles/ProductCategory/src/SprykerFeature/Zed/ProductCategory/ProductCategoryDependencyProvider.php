<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class ProductCategoryDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_TOUCH = 'touch facade';
    const FACADE_LOCALE = 'locale facade';
    const FACADE_URL = 'url facade';
    const FACADE_PRODUCT = 'product facade';
    const FACADE_CATEGORY = 'category facade';

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

        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->facade();
        };

        $container[self::FACADE_CATEGORY] = function (Container $container) {
            return $container->getLocator()->category()->facade();
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

        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->facade();
        };

        $container[self::FACADE_CATEGORY] = function (Container $container) {
            return $container->getLocator()->category()->facade();
        };

        return $container;
    }
}
