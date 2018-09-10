<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Communication\Plugin;

use Generated\Shared\Transfer\ChartDataTransfer;
use Spryker\Shared\Chart\ChartConfig;
use Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface;
use Spryker\Shared\Dashboard\Dependency\Plugin\DashboardPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesStatistics\Communication\SalesStatisticsCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesStatistics\Business\SalesStatisticsFacadeInterface getFacade()
 */
class StatusOrderChartPlugin extends AbstractPlugin implements ChartPluginInterface, DashboardPluginInterface
{
    public const NAME = 'status-orders';
    public const TITLE = 'Status orders';

    /**
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * @api
     *
     * @param string|null $dataIdentifier
     *
     * @return \Generated\Shared\Transfer\ChartDataTransfer
     */
    public function getChartData($dataIdentifier = null): ChartDataTransfer
    {
        $data = new ChartDataTransfer();
        $chartDataTraceTransfer = $this->getFacade()->getStatusOrderStatistic();
        $chartDataTraceTransfer->setType(ChartConfig::CHART_TYPE_PIE);
        $data->addTrace($chartDataTraceTransfer);
        $data->setKey($dataIdentifier);
        $data->setTitle(static::TITLE);

        return $data;
    }

    /**
     * @api
     *
     * @return string
     */
    public function render(): string
    {
        return $this->getFactory()
            ->getTwigEnvironment()
            ->createTemplate(
                sprintf("{{ chart('%s','%s') }}", static::NAME, static::NAME)
            )
            ->render([]);
    }
}
