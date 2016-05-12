<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Gui\GuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 */
class GuiTwigExtensionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @var \Spryker\Zed\Library\Twig\TwigFunctionInterface[]
     */
    protected $twigFunctions = [];

    /**
     * @var \Spryker\Zed\Library\Twig\TwigFilterInterface[]
     */
    protected $twigFilters = [];

    public function __construct()
    {
        $this->twigFunctions = $this->getFactory()->getProvidedDependency(GuiDependencyProvider::GUI_TWIG_FUNCTIONS);
        $this->twigFilters = $this->getFactory()->getProvidedDependency(GuiDependencyProvider::GUI_TWIG_FILTERS);
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
