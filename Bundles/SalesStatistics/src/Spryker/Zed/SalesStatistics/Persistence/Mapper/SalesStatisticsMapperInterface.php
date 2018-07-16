<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Persistence\Mapper;

use Generated\Shared\Transfer\ChartDataTraceTransfer;

interface SalesStatisticsMapperInterface
{
    /**
     * @param array $statistic
     *
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function mapCountStatisticToTransfer(array $statistic): ChartDataTraceTransfer;

    /**
     * @param array $statistic
     *
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function mapStatusOrderStatisticToTransfer(array $statistic): ChartDataTraceTransfer;

    /**
     * @param array $statistic
     *
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function mapTopOrderStatisticToTransfer(array $statistic): ChartDataTraceTransfer;
}
