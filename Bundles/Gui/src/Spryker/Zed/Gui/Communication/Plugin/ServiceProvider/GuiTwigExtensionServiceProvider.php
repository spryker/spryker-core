<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class GuiTwigExtensionServiceProvider implements ServiceProviderInterface
{

    /**
     * @var \Spryker\Zed\Library\Twig\TwigFunctionInterface[]
     */
    protected $twigFunctions = [];

    /**
     * @var \Spryker\Zed\Library\Twig\TwigFilterInterface[]
     */
    protected $twigFilters = [];

    /**
     * @param \Spryker\Zed\Library\Twig\TwigFunctionInterface[] $twigFunctions
     * @param \Spryker\Zed\Library\Twig\TwigFilterInterface[] $twigFilters
     */
    public function __construct(array $twigFunctions, array $twigFilters)
    {
        $this->twigFunctions = $twigFunctions;
        $this->twigFilters = $twigFilters;
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

                foreach ($this->twigFunctions as $function) {
                    $twig->addFunction($function);
                }

                foreach ($this->twigFilters as $filter) {
                    $twig->addFilter($filter);
                }

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
