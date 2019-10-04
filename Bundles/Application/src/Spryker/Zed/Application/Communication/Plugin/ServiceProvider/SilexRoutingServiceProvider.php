<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Application\Application as SprykerApplication;
use Spryker\Shared\Application\Business\Routing\SilexRouter;
use Symfony\Cmf\Component\Routing\ChainRouter;

/**
 * @deprecated Use `\Spryker\Yves\Router\Plugin\Router\YvesRouterPlugin` instead.
 */
class SilexRoutingServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Communication\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

    /**
     * @param \Spryker\Shared\Kernel\Communication\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app[SprykerApplication::SERVICE_ROUTER] = $app->share(
            $app->extend(SprykerApplication::SERVICE_ROUTER, function (ChainRouter $chainRouter) use ($app) {
                $chainRouter->add(new SilexRouter($app));

                return $chainRouter;
            })
        );
    }
}
