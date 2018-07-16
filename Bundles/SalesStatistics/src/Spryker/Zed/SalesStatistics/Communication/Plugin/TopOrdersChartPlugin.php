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
class TopOrdersChartPlugin extends AbstactChartPlugin
{
    public const COUNT_PRODUCT = 10;
    public const NAME = 'top-orders';
    public const TITLE = 'Top Orders';
    public const OPTIONS = [
        'orientation' => 'h',
    ];

    /**
     * @param string|null $dataIdentifier
     *
     * @return \Generated\Shared\Transfer\ChartDataTransfer
     */
    public function getChartData($dataIdentifier = null): ChartDataTransfer
    {
        $data = new ChartDataTransfer();
        $data->addTrace(
            $this->getFacade()->getTopOrderStatistic(static::COUNT_PRODUCT)
                ->addOption(static::OPTIONS)
                ->setType(ChartConfig::CHART_TYPE_BAR)
        );
        $data->setKey($dataIdentifier);
        $data->setTitle(static::TITLE);

        return $data;
    }
}
