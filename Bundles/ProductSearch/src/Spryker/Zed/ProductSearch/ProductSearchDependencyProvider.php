<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleBridge;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchBridge;

class ProductSearchDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_COLLECTOR = 'FACADE_COLLECTOR';
    const FACADE_LOCALE = 'facade locale';
    const FACADE_TOUCH = 'facade touch';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductSearchToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new ProductSearchToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_COLLECTOR] = function (Container $container) {
            return $container->getLocator()->collector()->facade();
        };

        return $container;
    }
}
