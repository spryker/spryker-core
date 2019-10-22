<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Chart\ChartPluginResolver;

use Spryker\Shared\ChartExtension\Dependency\Plugin\ChartPluginInterface;

interface ChartPluginResolverInterface
{
    /**
     * @param string $name
     *
     * @throws \Spryker\Shared\Chart\ChartPluginResolver\Exception\ChartPluginNotFoundException
     *
     * @return \Spryker\Shared\ChartExtension\Dependency\Plugin\ChartPluginInterface
     */
    public function getChartPluginByName(string $name): ChartPluginInterface;
}
