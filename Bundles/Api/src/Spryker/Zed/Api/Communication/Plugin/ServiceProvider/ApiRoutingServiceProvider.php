<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Plugin\ServiceProvider;

use RuntimeException;
use Silex\Application;
use Silex\ServiceControllerResolver;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Api\Business\Model\Router\ApiRouter;

class ApiRoutingServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Communication\Application $app
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    public function register(Application $app)
    {
        if (!($app['resolver'] instanceof ServiceControllerResolver)) {
            throw new RuntimeException('Register ServiceControllerServiceProvider first.');
        }

        $app->addRouter(new ApiRouter($app));
    }

    /**
     * @param \Spryker\Shared\Kernel\Communication\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}
