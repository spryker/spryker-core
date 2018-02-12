<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Chart\Plugin\Twig;

use Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface;

class TwigGeneralChart extends AbstractTwigChart implements TwigFunctionPluginInterface
{
    const TWIG_FUNCTION_NAME = 'spyChart';

    /**
     * @return string
     */
    protected function getTemplateName(): string
    {
        return '@Chart/template/chart.twig';
    }
}
