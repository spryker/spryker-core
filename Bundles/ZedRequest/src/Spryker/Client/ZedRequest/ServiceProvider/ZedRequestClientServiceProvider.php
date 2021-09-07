<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\Kernel\ContainerGlobals;

class ZedRequestClientServiceProvider implements ServiceProviderInterface
{
    /**
     * @var string
     */
    public const CLIENT_ZED_REQUEST = 'zed request client';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $containerGlobals = new ContainerGlobals();
        $containerGlobals[static::CLIENT_ZED_REQUEST] = function () {
            $container = new Container();

            return $container->getLocator()->zedRequest()->client();
        };
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}
