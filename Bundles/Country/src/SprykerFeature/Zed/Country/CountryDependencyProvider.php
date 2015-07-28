<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class CountryDependencyProvider extends AbstractBundleDependencyProvider
{

    const USER_QUERY_CONTAINER = 'USER_QUERY_CONTAINER';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::USER_QUERY_CONTAINER] = function (Container $container) {
            return $container->getLocator()->user()->queryContainer();
        };

        return $container;
    }

}
