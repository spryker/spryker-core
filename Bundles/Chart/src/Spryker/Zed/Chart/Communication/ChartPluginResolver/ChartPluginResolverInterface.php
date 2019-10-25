<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Chart\Communication\ChartPluginResolver;

use Spryker\Zed\ChartExtension\Dependency\Plugin\ChartPluginInterface;

interface ChartPluginResolverInterface
{
    /**
     * @param string $name
     *
     * @throws \Spryker\Zed\Chart\Communication\ChartPluginResolver\Exception\ChartPluginNotFoundException
     *
     * @return \Spryker\Zed\ChartExtension\Dependency\Plugin\ChartPluginInterface
     */
    public function getChartPluginByName(string $name): ChartPluginInterface;
}
