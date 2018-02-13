<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Chart;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ChartConfig extends AbstractSharedConfig
{
    const CHART_TYPE_BAR = 'bar';
    const CHART_TYPE_PIE = 'pie';
    const CHART_TYPE_LINE = 'scatter';

    /**
     * @return string[]
     */
    public function getChartTypes()
    {
        return [
            static::CHART_TYPE_BAR,
            static::CHART_TYPE_PIE,
            static::CHART_TYPE_LINE,
        ];
    }

    /**
     * @return string
     */
    public function getDefaultChartType()
    {
        return static::CHART_TYPE_BAR;
    }
}
