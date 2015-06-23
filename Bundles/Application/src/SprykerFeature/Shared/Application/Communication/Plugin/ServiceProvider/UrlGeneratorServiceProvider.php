<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

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

/**
 * Symfony CMF Routing component Provider for URL generation.
 *
 * @author Daniel Tschinder <daniel@tschinder.de>
 */
class UrlGeneratorServiceProvider implements ServiceProviderInterface
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
     * @codeCoverageIgnore
     */
    public function boot(Application $app)
    {
    }
}
