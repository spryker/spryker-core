<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\Kernel\ContainerGlobals;

class StorageClientServiceProvider implements ServiceProviderInterface
{
    public const CLIENT_STORAGE = 'storage client';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $containerGlobals = new ContainerGlobals();
        $containerGlobals[static::CLIENT_STORAGE] = function () {
            $container = new Container();

            return $container->getLocator()->storage()->client();
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
