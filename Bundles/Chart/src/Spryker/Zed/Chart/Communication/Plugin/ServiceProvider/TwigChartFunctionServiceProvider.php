<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Chart\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;

/**
 * @deprecated Use `\Spryker\Zed\Chart\Communication\Plugin\Twig\ChartTwigPlugin` instead.
 *
 * @method \Spryker\Zed\Chart\Communication\ChartCommunicationFactory getFactory()
 * @method \Spryker\Zed\Chart\Business\ChartFacadeInterface getFacade()
 * @method \Spryker\Zed\Chart\ChartConfig getConfig()
 */
class TwigChartFunctionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app): void
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (Environment $twig) {
                return $this->registerChartTwigFunctions($twig);
            })
        );
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app): void
    {
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function registerChartTwigFunctions(Environment $twig): Environment
    {
        foreach ($this->getChartTwigFunctions() as $function) {
            $twig->addFunction($function->getName(), $function);
        }

        return $twig;
    }

    /**
     * @return array
     */
    protected function getChartTwigFunctions(): array
    {
        $functions = [];
        foreach ($this->getFactory()->getTwigChartFunctionPlugins() as $twigFunctionPlugin) {
            $functions = array_merge($functions, $twigFunctionPlugin->getChartFunctions());
        }

        return $functions;
    }
}
