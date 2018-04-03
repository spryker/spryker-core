<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Chart\Plugin\Twig;

use Spryker\Shared\Chart\Dependency\Plugin\ChartLayoutablePluginInterface;
use Spryker\Shared\Chart\Dependency\Plugin\TwigChartFunctionPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig_Environment;
use Twig_SimpleFunction;

/**
 * @method \Spryker\Yves\Chart\ChartFactory getFactory()
 * @method \Spryker\Yves\Chart\ChartConfig getConfig()
 */
abstract class AbstractTwigChartPlugin extends AbstractPlugin implements TwigChartFunctionPluginInterface
{
    const TWIG_FUNCTION_NAME = 'spyChart';

    /**
     * @return string
     */
    public function getName()
    {
        return static::TWIG_FUNCTION_NAME;
    }

    /**
     * @return \Twig_SimpleFunction[]
     */
    public function getChartFunctions()
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
     * @param string $chartPluginName
     * @param string|null $dataIdentifier
     *
     * @return string
     */
    public function renderChart(Twig_Environment $twig, $chartPluginName, $dataIdentifier = null)
    {
        $context = $this->getChartContext($chartPluginName, $dataIdentifier);
        $rendered = $twig->render($this->getTemplateName(), $context);

        return $rendered;
    }

    /**
     * @return string
     */
    abstract protected function getTemplateName();

    /**
     * @param string $chartPluginName
     * @param string|null $dataIdentifier
     *
     * @return array
     */
    protected function getChartContext($chartPluginName, $dataIdentifier)
    {
        $chartPlugin = $this->getChartPluginByName($chartPluginName);

        $context = [
            'data' => $chartPlugin->getChartData($dataIdentifier),
            'layout' => $this->getConfig()->getDefaultChartLayout(),
        ];
        if ($chartPlugin instanceof ChartLayoutablePluginInterface) {
            $context['layout'] = $chartPlugin->getChartLayout();
        }

        return $context;
    }

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
            ->createChartPluginCollection()
            ->getChartPluginByName($pluginName);
    }
}
