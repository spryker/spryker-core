<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Business;

use Generated\Shared\Transfer\ChartDataTraceTransfer;

interface SalesStatisticsFacadeInterface
{
    /**
     * Specification:
     * - Get order statistic for last days
     *
     * @api
     *
     * @param int $days
     *
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function getOrderStatisticByCountDay($days): ChartDataTraceTransfer;

    /**
     * Specification:
     * - Get sales statistic for each order status
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function getStatusOrderStatistic(): ChartDataTraceTransfer;

    /**
     * Specification:
     * - Get top products statistic
     *
     * @api
     *
     * @param int $countProduct
     *
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function getTopOrderStatistic(int $countProduct): ChartDataTraceTransfer;
}
