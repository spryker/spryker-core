<?php

namespace SprykerFeature\Zed\Distributor;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class DistributorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_QUEUE = 'facade queue';
    const ITEM_PROCESSORS = 'item processors';
    const QUERY_EXPANDERS = 'query expanders';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_QUEUE] = function (Container $container) {
            return $container->getLocator()->queue()->facade();
        };
        $container[self::ITEM_PROCESSORS] = function (Container $container) {
            return [];
        };
        $container[self::QUERY_EXPANDERS] = function (Container $container) {
            return [];
        };

        return $container;
    }

}
