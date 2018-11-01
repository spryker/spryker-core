<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Chart;

use Generated\Shared\Transfer\ChartLayoutTransfer;
use Spryker\Shared\Kernel\AbstractSharedConfig;

class ChartConfig extends AbstractSharedConfig
{
    public const CHART_TYPE_BAR = 'bar';
    public const CHART_TYPE_PIE = 'pie';
    public const CHART_TYPE_LINE = 'scatter';

    /**
     * @return string[]
     */
    public function getChartTypes(): array
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
    public function getDefaultChartType(): string
    {
        return static::CHART_TYPE_BAR;
    }

    /**
     * @return \Generated\Shared\Transfer\ChartLayoutTransfer
     */
    public function getDefaultChartLayout(): ChartLayoutTransfer
    {
        return new ChartLayoutTransfer();
    }
}
