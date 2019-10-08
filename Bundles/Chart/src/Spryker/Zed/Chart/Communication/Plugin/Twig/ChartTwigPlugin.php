<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Chart\Communication\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;

/**
 * @method \Spryker\Zed\Chart\Communication\ChartCommunicationFactory getFactory()
 * @method \Spryker\Zed\Chart\ChartConfig getConfig()
 * @method \Spryker\Zed\Chart\Business\ChartFacadeInterface getFacade()
 */
class ChartTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        return $this->registerChartTwigFunctions($twig);
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
     * @return \Twig\TwigFunction[]
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
