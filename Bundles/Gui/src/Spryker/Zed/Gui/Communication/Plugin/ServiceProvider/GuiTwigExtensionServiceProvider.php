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
use Spryker\Zed\Gui\Communication\Plugin\Twig\ActionButtons\BackActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ActionButtons\CreateActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ActionButtons\EditActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ActionButtons\ViewActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\UrlFunction;
use Spryker\Zed\Library\Twig\TwigFunction;

class GuiTwigExtensionServiceProvider implements ServiceProviderInterface
{

    /**
     * @var TwigFunction[]
     */
    protected $twigFunctions = [];

    /**
     * @param array $twigPlugins
     */
    public function __construct(array $twigPlugins)
    {
        $this->twigFunctions = $twigPlugins;
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (\Twig_Environment $twig) {

                foreach ($this->twigFunctions as $plugin) {
                    $twig->addFunction($plugin);
                }
//                $twig->addFunction(new FormatPriceFunction());
//                $twig->addFunction(new AssetsPathFunction());
//                $twig->addFunction(new BackActionButtonFunction());
//                $twig->addFunction(new CreateActionButtonFunction());
//                $twig->addFunction(new ViewActionButtonFunction());
//                $twig->addFunction(new EditActionButtonFunction());
//                $twig->addFunction(new UrlFunction());

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
