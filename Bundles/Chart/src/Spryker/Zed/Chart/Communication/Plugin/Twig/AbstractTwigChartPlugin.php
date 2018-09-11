<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Chart\Communication\Plugin\Twig;

use Spryker\Shared\Chart\Dependency\Plugin\ChartLayoutablePluginInterface;
use Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface;
use Spryker\Shared\Chart\Dependency\Plugin\TwigChartFunctionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig_Environment;
use Twig_SimpleFunction;

/**
 * @method \Spryker\Zed\Chart\Communication\ChartCommunicationFactory getFactory()
 * @method \Spryker\Zed\Chart\ChartConfig getConfig()
 */
abstract class AbstractTwigChartPlugin extends AbstractPlugin implements TwigChartFunctionPluginInterface
{
    const TWIG_FUNCTION_NAME = 'chart';

    /**
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::TWIG_FUNCTION_NAME;
    }

    /**
     * @api
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getChartFunctions(): array
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
     * @api
     *
     * @param \Twig_Environment $twig
     * @param string $chartPluginName
     * @param string|null $dataIdentifier
     *
     * @return string
     */
    public function renderChart(Twig_Environment $twig, $chartPluginName, $dataIdentifier = null): string
    {
        $context = $this->getChartContext($chartPluginName, $dataIdentifier);
        $rendered = $twig->render($this->getTemplateName(), $context);

        return $rendered;
    }

    /**
     * @return string
     */
    abstract protected function getTemplateName(): string;

    /**
     * @param string $chartPluginName
     * @param string|null $dataIdentifier
     *
     * @return array
     */
    protected function getChartContext($chartPluginName, $dataIdentifier): array
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
    protected function getDefaultTwigOptions(): array
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
    protected function getChartPluginByName($pluginName): ChartPluginInterface
    {
        return $this->getFactory()
            ->createChartPluginCollection()
            ->getChartPluginByName($pluginName);
    }
}
