<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Cmf\Component\Routing\ChainRouter;

/**
 * @method \Spryker\Zed\Application\Business\ApplicationFacade getFacade()
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 */
class RoutingServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['url_matcher'] = $app->share(function () use ($app) {
            /** @var \Symfony\Cmf\Component\Routing\ChainRouter $chainRouter */
            $chainRouter = $app['routers'];
            $chainRouter->setContext($app['request_context']);

            return $chainRouter;
        });

        $app['routers'] = $app->share(function () use ($app) {
            return new ChainRouter($app['logger']);
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
