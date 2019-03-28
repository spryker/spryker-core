<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ChartDataTraceTransfer;
use Spryker\Zed\SalesStatistics\Persistence\SalesStatisticsRepository;

class SalesStatisticsMapper
{
    public const DECIMAL = 100;

    /**
     * @param array $statistic
     *
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function mapCountStatisticToTransfer(array $statistic): ChartDataTraceTransfer
    {
        $chartDataTraceTransfer = new ChartDataTraceTransfer();

        foreach ($statistic as $statisticItem) {
            $chartDataTraceTransfer->addLabel($statisticItem[SalesStatisticsRepository::DATE]);
            $chartDataTraceTransfer->addValue($statisticItem[SalesStatisticsRepository::COUNT]);
        }

        return $chartDataTraceTransfer;
    }

    /**
     * @param array $statistic
     *
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function mapStatusOrderStatisticToTransfer(array $statistic): ChartDataTraceTransfer
    {
        $chartDataTraceTransfer = new ChartDataTraceTransfer();
        foreach ($statistic as $statisticItem) {
            $chartDataTraceTransfer->addLabel($statisticItem[SalesStatisticsRepository::STATUS_NAME]);
            /** @var mixed $total */
            $total = $statisticItem[SalesStatisticsRepository::TOTAL] / static::DECIMAL;
            $chartDataTraceTransfer->addValue($total);
        }

        return $chartDataTraceTransfer;
    }

    /**
     * @param array $statistic
     *
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function mapTopOrderStatisticToTransfer(array $statistic): ChartDataTraceTransfer
    {
        $chartDataTraceTransfer = new ChartDataTraceTransfer();
        foreach ($statistic as $statisticItem) {
            $chartDataTraceTransfer->addLabel($statisticItem[SalesStatisticsRepository::COUNT]);
            $chartDataTraceTransfer->addValue($statisticItem[SalesStatisticsRepository::ITEM_NAME]);
            $chartDataTraceTransfer->addOption($statisticItem[SalesStatisticsRepository::ITEM_SKU]);
        }

        return $chartDataTraceTransfer;
    }
}
