<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Application\Application as SprykerApplication;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Cmf\Component\Routing\ChainRouter;

/**
 * @deprecated Use `\Spryker\Zed\Router\Communication\Plugin\Application\RouterApplicationPlugin` instead.
 *
 * Requesting the `url_matcher` from the container returned an instance of the ChainRouter. Instead of using several keys
 * pointing to the ChainRouter we only use `routers` from now on.
 *
 * @method \Spryker\Zed\Application\Business\ApplicationFacadeInterface getFacade()
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 */
class RoutingServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['url_matcher'] = $app->share(function () use ($app) {
            /** @var \Symfony\Cmf\Component\Routing\ChainRouter $chainRouter */
            $chainRouter = $app[SprykerApplication::SERVICE_ROUTER];
            $chainRouter->setContext($app['request_context']);

            return $chainRouter;
        });

        $app[SprykerApplication::SERVICE_ROUTER] = $app->share(function () {
            return new ChainRouter();
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
