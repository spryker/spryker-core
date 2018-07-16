<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Communication\Plugin;

use Generated\Shared\Transfer\ChartDataTransfer;
use Spryker\Shared\Chart\ChartConfig;

/**
 * @method \Spryker\Zed\SalesStatistics\Communication\SalesStatisticsCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesStatistics\Business\SalesStatisticsFacadeInterface getFacade()
 */
class CountOrderChartPlugin extends AbstactChartPlugin
{
    public const NAME = 'count-orders';
    public const TITLE = 'Count orders';
    public const DAYS = 7;

    /**
     * @param string|null $dataIdentifier
     *
     * @return \Generated\Shared\Transfer\ChartDataTransfer
     */
    public function getChartData($dataIdentifier = null): ChartDataTransfer
    {
        $data = new ChartDataTransfer();
        $chartDataTraceTransfer = $this->getFacade()->getOrderStatisticByCountDay(static::DAYS);
        $chartDataTraceTransfer->setType(ChartConfig::CHART_TYPE_BAR);
        $data->addTrace($chartDataTraceTransfer);
        $data->setKey($dataIdentifier);
        $data->setTitle(static::TITLE);

        return $data;
    }
}
