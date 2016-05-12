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
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (\Twig_Environment $twig) {

                foreach ($this->getProvidedTwigFunctions() as $function) {
                    $twig->addFunction($function);
                }

                foreach ($this->getProvidedTwigFilters() as $filter) {
                    $twig->addFilter($filter);
                }

                return $twig;
            })
        );
    }

    /**
     * @return \Twig_SimpleFunction[]
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function getProvidedTwigFunctions()
    {
        return $this->getFactory()
            ->getProvidedDependency(GuiDependencyProvider::GUI_TWIG_FUNCTIONS);
    }

    /**
     * @return \Twig_SimpleFilter[]
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function getProvidedTwigFilters()
    {
        return $this->getFactory()
            ->getProvidedDependency(GuiDependencyProvider::GUI_TWIG_FILTERS);
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
