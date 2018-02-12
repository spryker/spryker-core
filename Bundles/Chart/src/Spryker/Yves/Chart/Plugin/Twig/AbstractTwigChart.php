<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Chart\Plugin\Twig;

use Silex\Application;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface;
use Twig_Environment;
use Twig_SimpleFunction;

/**
 * @method \Spryker\Yves\Chart\ChartFactory getFactory()
 */
abstract class AbstractTwigChart extends AbstractPlugin implements TwigFunctionPluginInterface
{
    const TWIG_FUNCTION_NAME = 'spyChart';

    /**
     * @param \Silex\Application $application
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions(Application $application)
    {
        return [
            new Twig_SimpleFunction(
                static::TWIG_FUNCTION_NAME,
                [$this, 'renderChart'],
                $this->getDefaultTwigOptions()
            ),
        ];
    }

    /**
     * @param \Twig_Environment $twig
     * @param string $chartType
     * @param string $dataIdentifier
     *
     * @return string
     */
    public function renderChart(Twig_Environment $twig, $chartType, $dataIdentifier)
    {
        $chartPlugin = $this->getChartPluginByName($chartType);
        $rendered = $twig->render($this->getTemplateName(), [
            'data' => $chartPlugin->getChartData($dataIdentifier),
            'layout' => $chartPlugin->getChartLayout(),
        ]);

        return $rendered;
    }

    /**
     * @return string
     */
    abstract protected function getTemplateName();

    /**
     * @return array
     */
    protected function getDefaultTwigOptions()
    {
        return [
            'is_safe' => ['html'],
            'needs_environment' => true,
        ];
    }

    /**
     * @param string $pluginName
     *
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface
     */
    protected function getChartPluginByName($pluginName)
    {
        return $this->getFactory()
            ->createChartPluginProvider()
            ->getChartPluginByName($pluginName);
    }
}
