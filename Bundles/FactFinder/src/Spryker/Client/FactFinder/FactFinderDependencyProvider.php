<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class FactFinderDependencyProvider extends AbstractDependencyProvider
{

    const SERVICE_ZED = 'ff zed service';
    const CLIENT_SESSION = 'session client';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::SERVICE_ZED] = function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        };
        $container[self::CLIENT_SESSION] = function (Container $container) {
            return $container->getLocator()->session()->client();
        };

        return $container;
    }
}
