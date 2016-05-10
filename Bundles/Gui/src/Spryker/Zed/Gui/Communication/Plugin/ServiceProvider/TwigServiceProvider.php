<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Gui\Communication\Plugin\Twig\AssetsPathFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\UrlFunction;

class TwigServiceProvider implements ServiceProviderInterface
{

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        // try if that works without share as well, maybe it's not needed and extend is enough
        $app['twig'] = $app->share(
            $app->extend('twig', function (\Twig_Environment $twig) {
                $twig->addFunction(new AssetsPathFunction());
                $twig->addFunction(new UrlFunction());

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
