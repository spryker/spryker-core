<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Symfony CMF Routing component Provider for URL generation.
 *
 * @deprecated Use Spryker\Shared\Application\ServiceProvider\UrlGeneratorServiceProvider instead
 */
class UrlGeneratorServiceProvider implements ServiceProviderInterface
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
        $app['url_generator'] = $app->share(function ($app) {
            $app->flush();

            return $app['routers'];
        });
    }

    /**
     * @codeCoverageIgnore
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}
