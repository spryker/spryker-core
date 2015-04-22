<?php

/*
 * This file is part of the SilexRouting extension.
 *
 * (c) Daniel Tschinder
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SprykerFeature\Shared\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Cmf\Component\Routing\ChainRouter;

/**
 * SilexRouting provider for advanced routing.
 *
 * @author Daniel Tschinder <daniel@tschinder.de>
 */
class RoutingServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['url_matcher'] = $app->share(function () use ($app) {
            /* @var ChainRouter $chainRouter */
            $chainRouter = $app['routers'];
            $chainRouter->setContext($app['request_context']);

            return $chainRouter;
        });

        $app['routers'] = $app->share(function () use ($app) {
            return new ChainRouter($app['logger']);
        });
    }

    /**
     * @codeCoverageIgnore
     */
    public function boot(Application $app)
    {
    }
}
