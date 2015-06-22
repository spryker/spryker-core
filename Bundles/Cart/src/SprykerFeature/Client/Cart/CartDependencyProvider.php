<?php

namespace SprykerFeature\Client\Cart;

use SprykerEngine\Client\Kernel\AbstractDependencyProvider;
use SprykerEngine\Client\Kernel\Container;

class CartDependencyProvider extends AbstractDependencyProvider
{

    const SERVICE_STORAGE = 'storage service';

    const SESSION = 'session';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::SESSION] = function (Container $container) {
            return $container->getLocator()->application()->pluginPimple();
        };

        return $container;
    }

}
