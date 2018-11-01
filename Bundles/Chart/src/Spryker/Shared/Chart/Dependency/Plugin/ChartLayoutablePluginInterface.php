<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Chart\Dependency\Plugin;

use Generated\Shared\Transfer\ChartLayoutTransfer;

interface ChartLayoutablePluginInterface extends ChartPluginInterface
{
    /**
     * Specification:
     * - Returns a ChartLayoutTransfer object with layout for charts.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ChartLayoutTransfer
     */
    public function getChartLayout(): ChartLayoutTransfer;
}
