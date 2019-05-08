<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Chart\Dependency\Plugin;

interface TwigChartFunctionPluginInterface
{
    /**
     * @api
     *
     * @return \Twig\TwigFunction[]
     */
    public function getChartFunctions(): array;
}
