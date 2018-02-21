<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Chart\Communication\Plugin\Twig;

class TwigPieChartPlugin extends AbstractTwigChartPlugin
{
    const TWIG_FUNCTION_NAME = 'spyPieChart';

    /**
     * @return string
     */
    protected function getTemplateName(): string
    {
        return '@Chart/pie-chart.twig';
    }
}
