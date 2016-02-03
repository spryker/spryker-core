<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Client\Cms;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CmsDependencyProvider extends AbstractDependencyProvider
{

    const KV_STORAGE = 'kv storage';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::KV_STORAGE] = function (Container $container) {
            return $container->getLocator()->storage()->client();
        };

        return $container;
    }

}
