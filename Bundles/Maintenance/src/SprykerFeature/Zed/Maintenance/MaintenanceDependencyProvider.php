<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class MaintenanceDependencyProvider extends AbstractBundleDependencyProvider
{

    const STORAGE_CLIENT = 'STORAGE_CLIENT';

    const SEARCH_CLIENT = 'SEARCH_CLIENT';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {

        $container[self::STORAGE_CLIENT] = function (Container $container) {
            return $container->getLocator()->storage()->client();
        };

        $container[self::SEARCH_CLIENT] = function (Container $container) {
            return $container->getLocator()->search()->client();
        };

        return $container;
    }


}
