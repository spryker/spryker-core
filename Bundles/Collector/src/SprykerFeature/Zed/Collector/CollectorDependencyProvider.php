<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class CollectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_COLLECTOR = 'collector_facade';

    const QUERY_CONTAINER_COLLECTOR = 'collector_query_container';

    const FACADE_LOCALE = 'locale_facade';

    /**
     * @var Container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->provideLocaleFacade($container);

        $container[self::QUERY_CONTAINER_COLLECTOR] = function (Container $container) {
            return $container->getLocator()->collector()->queryContainer();
        };

        return $container;
    }

    /**
     * @var Container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->provideLocaleFacade($container);

        $container[self::FACADE_COLLECTOR] = function (Container $container) {
            return $container->getLocator()->collector()->facade();
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    private function provideLocaleFacade(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return $container->getLocator()->locale()->facade();
        };

        return $container;
    }

}
