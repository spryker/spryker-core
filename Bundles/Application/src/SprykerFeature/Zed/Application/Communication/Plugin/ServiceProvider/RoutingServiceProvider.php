<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Cmf\Component\Routing\ChainRouter;

class RoutingServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['url_matcher'] = $app->share(function () use ($app) {
            /** @var ChainRouter $chainRouter */
            $chainRouter = $app['routers'];
            $chainRouter->setContext($app['request_context']);

            return $chainRouter;
        });

        $app['routers'] = $app->share(function () use ($app) {
            return new ChainRouter($app['logger']);
        });
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }

}
