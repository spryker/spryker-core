<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ZedRequest;

use SprykerEngine\Client\Kernel\AbstractDependencyProvider;
use SprykerEngine\Client\Kernel\Container;

class ZedRequestDependencyProvider extends AbstractDependencyProvider
{

    const CLIENT_AUTH = 'auth client';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::CLIENT_AUTH] = function (Container $container) {
            return $container->getLocator()->auth()->client();
        };

        return $container;
    }

}
