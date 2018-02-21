<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Chart\Plugin\Twig;

use Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface;

class TwigLineChart extends AbstractTwigChart implements TwigFunctionPluginInterface
{
    const TWIG_FUNCTION_NAME = 'spyLineChart';

    /**
     * @return string
     */
    protected function getTemplateName(): string
    {
        return '@Chart/line-chart.twig';
    }
}
