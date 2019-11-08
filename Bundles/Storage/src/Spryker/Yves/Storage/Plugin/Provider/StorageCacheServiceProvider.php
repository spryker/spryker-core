<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Storage\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Storage\StorageConstants;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use `\Spryker\Yves\Storage\Plugin\EventDispatcher\StorageCacheEventDispatcherPlugin` instead.
 *
 * @method \Spryker\Client\Storage\StorageClientInterface getClient()
 */
class StorageCacheServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app->finish(function (Request $request) use ($app) {
            if (isset($app[StorageConstants::STORAGE_CACHE_STRATEGY])) {
                $this->getClient()->persistCacheForRequest($request, $app[StorageConstants::STORAGE_CACHE_STRATEGY]);
            }
        });
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
