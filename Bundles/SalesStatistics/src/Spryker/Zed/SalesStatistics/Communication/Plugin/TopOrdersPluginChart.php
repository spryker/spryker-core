<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Communication\Plugin;

use Generated\Shared\Transfer\ChartDataTraceTransfer;
use Generated\Shared\Transfer\ChartDataTransfer;
use Generated\Shared\Transfer\ChartLayoutTransfer;
use Spryker\Shared\Chart\ChartConfig;
use Spryker\Shared\Chart\Dependency\Plugin\ChartLayoutablePluginInterface;
use Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface;
use Spryker\Shared\Dashboard\Dependency\Plugin\DashboardPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesStatistics\Communication\SalesStatisticsCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesStatistics\Business\SalesStatisticsFacadeInterface getFacade()
 */
class TopOrdersPluginChart extends AbstractPlugin implements ChartPluginInterface, DashboardPluginInterface, ChartLayoutablePluginInterface
{
    public const COUNT_PRODUCT = 10;
    public const NAME = 'top-orders';
    public const TITLE = 'Top Orders';

    /**
     * @return string
     */
    public function render(): string
    {
        return $this->getFactory()
            ->getTwigEnvironment()
            ->createTemplate("{{chart('" . static::NAME . "', '" . static::NAME . "')}}")
            ->render([]);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * @param string|null $dataIdentifier
     *
     * @return \Generated\Shared\Transfer\ChartDataTransfer
     */
    public function getChartData($dataIdentifier = null): ChartDataTransfer
    {
        $data = new ChartDataTransfer();
        $data->addTrace($this->getChartDataTraceTransfer());
        $data->setKey($dataIdentifier);
        $data->setTitle(static::TITLE);

        return $data;
    }

    /**
     * @return \Generated\Shared\Transfer\ChartLayoutTransfer
     */
    public function getChartLayout(): ChartLayoutTransfer
    {
        return new ChartLayoutTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    protected function getChartDataTraceTransfer(): ChartDataTraceTransfer
    {
        $result = $this->getFacade()->getTopOrderStatistic(static::COUNT_PRODUCT);

        $trace = new ChartDataTraceTransfer();
        $trace->setType(ChartConfig::CHART_TYPE_BAR);
        $trace->setLabels($result->getValues());
        $trace->setValues($result->getLabels());
        $trace->addOption($this->getOptions());

        return $trace;
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            'orientation' => 'h',
        ];
    }
}
