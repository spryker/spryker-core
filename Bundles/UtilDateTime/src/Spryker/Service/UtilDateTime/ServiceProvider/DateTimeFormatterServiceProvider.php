<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDateTime\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Service\UtilDateTime\Model\DateTimeFormatterTwigExtension;
use Spryker\Service\UtilDateTime\UtilDateTimeService;
use Twig\Environment;

/**
 * @deprecated Use `\Spryker\Service\UtilDateTime\Plugin\Twig\DateTimeFormatterTwigPlugin` instead.
 */
class DateTimeFormatterServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $utilDateTimeService = new UtilDateTimeService();

        $app['twig'] = $app->share(
            $app->extend('twig', function (Environment $twig) use ($utilDateTimeService) {
                $twig->addExtension(new DateTimeFormatterTwigExtension($utilDateTimeService));

                return $twig;
            })
        );
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
