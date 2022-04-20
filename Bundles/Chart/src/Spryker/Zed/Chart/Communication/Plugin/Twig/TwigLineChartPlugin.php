<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Chart\Communication\Plugin\Twig;

/**
 * @deprecated {@link \Spryker\Zed\ChartGui\Communication\Plugin\Twig\Chart\LineChartTwigPlugin} instead.
 * @method \Spryker\Zed\Chart\ChartConfig getConfig()
 * @method \Spryker\Zed\Chart\Communication\ChartCommunicationFactory getFactory()
 * @method \Spryker\Zed\Chart\Business\ChartFacadeInterface getFacade()
 */
class TwigLineChartPlugin extends AbstractTwigChartPlugin
{
    /**
     * @var string
     */
    public const TWIG_FUNCTION_NAME = 'lineChart';

    /**
     * @return string
     */
    protected function getTemplateName(): string
    {
        return '@Chart/_template/line-chart.twig';
    }
}
