<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

class UrlGeneratorServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['url_generator'] = $app->share(function ($app) {
            $app->flush();

            return $app['routers'];
        });
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }

}
