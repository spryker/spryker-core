<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class PayolutionDependencyProvider extends AbstractBundleDependencyProvider
{

    const QUERY_CONTAINER_OSM = 'query container osm';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[self::QUERY_CONTAINER_OSM] = function (Container $container) {
            return $container->getLocator()->oms()->queryContainer();
        };

        return $container;
    }

}
