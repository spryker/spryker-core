<?php

namespace Spryker\Zed\CmsCollector;

use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCollectorBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsCollectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_COLLECTOR = 'FACADE_COLLECTOR';
    const SERVICE_DATA_READER = 'SERVICE_DATA_READER';
    const QUERY_CONTAINER_TOUCH = 'QUERY_CONTAINER_TOUCH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::SERVICE_DATA_READER] = function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        };

        $container[self::FACADE_COLLECTOR] = function (Container $container) {
            return new CmsCollectorToCollectorBridge($container->getLocator()->collector()->facade());
        };

        $container[self::QUERY_CONTAINER_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->queryContainer();
        };

        return $container;
    }
}
