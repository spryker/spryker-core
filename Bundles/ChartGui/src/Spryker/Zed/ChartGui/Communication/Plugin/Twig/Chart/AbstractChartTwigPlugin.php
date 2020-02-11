<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ChartGui\Communication\Plugin\Twig\Chart;

use Spryker\Shared\Chart\ChartPluginCollection\Exception\ChartPluginNotFoundException;
use Spryker\Shared\Chart\Dependency\Plugin\ChartLayoutablePluginInterface;
use Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface;
use Spryker\Shared\Chart\Dependency\Plugin\TwigChartFunctionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \Spryker\Zed\ChartGui\Communication\ChartGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ChartGui\ChartGuiConfig getConfig()
 */
abstract class AbstractChartTwigPlugin extends AbstractPlugin implements TwigChartFunctionPluginInterface
{
    public const TWIG_FUNCTION_NAME = 'chart';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::TWIG_FUNCTION_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Twig\TwigFunction[]
     */
    public function getChartFunctions(): array
    {
        return [
            new TwigFunction(
                static::TWIG_FUNCTION_NAME,
                [$this, 'renderChart'],
                $this->getDefaultTwigOptions()
            ),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param string $chartPluginName
     * @param string|null $dataIdentifier
     *
     * @return string
     */
    public function renderChart(Environment $twig, $chartPluginName, $dataIdentifier = null): string
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
        $chartPlugin = $this->resolveChartPluginByName($chartPluginName);

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
     * @throws \Spryker\Shared\Chart\ChartPluginCollection\Exception\ChartPluginNotFoundException
     *
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface
     */
    protected function resolveChartPluginByName(string $pluginName): ChartPluginInterface
    {
        foreach ($this->getFactory()->getChartPlugins() as $chartPlugin) {
            if ($chartPlugin->getName() === $pluginName) {
                return $chartPlugin;
            }
        }

        throw new ChartPluginNotFoundException(sprintf('Chart plugin "%s" was not found', $pluginName));
    }
}
