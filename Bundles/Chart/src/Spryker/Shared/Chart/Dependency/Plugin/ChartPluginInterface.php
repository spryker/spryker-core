<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Chart\Dependency\Plugin;

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
    public function getName();

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
    public function getChartData($dataIdentifier = null);

    /**
     * Specification:
     * - Returns a ChartLayoutTransfer object with layout for charts.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ChartLayoutTransfer
     */
    public function getChartLayout();
}
