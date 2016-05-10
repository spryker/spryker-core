<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Gui\Communication\Plugin\Twig\AssetsPathFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\FormatPriceFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia\BackActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia\CreateActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia\EditActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia\ViewActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ListGroupFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ModalFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\PanelFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\UrlFunction;

class TwigExtensionServiceProvider implements ServiceProviderInterface
{

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (\Twig_Environment $twig) {

                $twig->addFunction(new FormatPriceFunction());
                $twig->addFunction(new ListGroupFunction());
                $twig->addFunction(new ModalFunction());
                $twig->addFunction(new PanelFunction());
                $twig->addFunction(new AssetsPathFunction());
                $twig->addFunction(new BackActionButtonFunction());
                $twig->addFunction(new CreateActionButtonFunction());
                $twig->addFunction(new ViewActionButtonFunction());
                $twig->addFunction(new EditActionButtonFunction());
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
