<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\ZedRequest;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class ZedRequestDependencyProvider extends AbstractDependencyProvider
{

    const CLIENT_AUTH = 'auth client';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::CLIENT_AUTH] = function (Container $container) {
            return $container->getLocator()->auth()->client();
        };

        return $container;
    }

}
