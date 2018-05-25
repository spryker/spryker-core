<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Chart\Dependency\Plugin;

use Generated\Shared\Transfer\ChartDataTransfer;

interface ChartPluginInterface
{
    /**
     * Specification:
     * - Returns a plugin name.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Specification:
     * - Returns a ChartDataTransfer object with data for charts.
     *
     * @api
     *
     * @param string|null $dataIdentifier
     *
     * @return \Generated\Shared\Transfer\ChartDataTransfer
     */
    public function getChartData($dataIdentifier = null): ChartDataTransfer;


}
