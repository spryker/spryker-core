<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\Kernel\ContainerGlobals;

class SearchClientServiceProvider implements ServiceProviderInterface
{
    public const CLIENT_SEARCH = 'search client';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $containerGlobals = new ContainerGlobals();
        $containerGlobals[static::CLIENT_SEARCH] = function () {
            $container = new Container();

            return $container->getLocator()->search()->client();
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
