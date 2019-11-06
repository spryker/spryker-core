<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Session\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\Kernel\ContainerGlobals;

/**
 * @deprecated Will be removed without replacement.
 */
class SessionClientServiceProvider implements ServiceProviderInterface
{
    public const CLIENT_SESSION = 'session client';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $containerGlobals = new ContainerGlobals();
        $containerGlobals[static::CLIENT_SESSION] = function () {
            $container = new Container();

            return $container->getLocator()->session()->client();
        };
    }

    /**
     * @param \Silex\Application $application
     *
     * @return void
     */
    public function boot(Application $application)
    {
    }
}
