<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class ProductDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_LOCALE = 'facade locale';
    const FACADE_URL = 'facade url';
    const FACADE_TOUCH = 'facade touch';
    const FACADE_PRODUCT_CATEGORIES = 'facade product categories';
    const FACADE_PRODUCT_OPTIONS = 'facade product options';
    const QUERY_CONTAINER_PRODUCT = 'query container product ';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return $container->getLocator()->locale()->facade();
        };

        $container[self::FACADE_URL] = function (Container $container) {
            return $container->getLocator()->url()->facade();
        };

        $container[self::FACADE_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->facade();
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

        $container[self::FACADE_PRODUCT_CATEGORIES] = function (Container $container) {
            return $container->getLocator()->productCategory()->facade();
        };

        $container[self::FACADE_PRODUCT_OPTIONS] = function (Container $container) {
            return $container->getLocator()->productOption()->facade();
        };

        $container[self::FACADE_URL] = function (Container $container) {
            return $container->getLocator()->url()->facade();
        };

        $container[self::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->queryContainer();
        };

        return $container;
    }

}
