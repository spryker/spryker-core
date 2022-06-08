<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ChartGui\Communication\Plugin\Twig\Chart;

/**
 * @method \Spryker\Zed\ChartGui\ChartGuiConfig getConfig()
 * @method \Spryker\Zed\ChartGui\Business\ChartGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ChartGui\Communication\ChartGuiCommunicationFactory getFactory()
 */
class PieChartTwigPlugin extends AbstractChartTwigPlugin
{
    /**
     * @var string
     */
    public const TWIG_FUNCTION_NAME = 'pieChart';

    /**
     * @return string
     */
    protected function getTemplateName(): string
    {
        return '@ChartGui/Chart/pie-chart.twig';
    }
}
