<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Customer;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CustomerDependencyProvider extends AbstractDependencyProvider
{

    const SERVICE_SESSION = 'session service';
    const SERVICE_ZED = 'zed service';

    /**
     * @param Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::SERVICE_SESSION] = function (Container $container) {
            return $container->getLocator()->session()->client();
        };

        $container[self::SERVICE_ZED] = function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        };

        return $container;
    }

}
