<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ChartExtension\Dependency\Plugin;

interface ChartTwigFunctionPluginInterface
{
    /**
     * Specification:
     * - Defines the list of chart functions.
     *
     * @api
     *
     * @return \Twig\TwigFunction[]
     */
    public function getChartTwigFunctions(): array;
}
