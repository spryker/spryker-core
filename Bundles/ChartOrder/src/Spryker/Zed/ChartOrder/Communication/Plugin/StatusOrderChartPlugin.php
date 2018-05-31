<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ChartOrder\Communication\Plugin;

use Generated\Shared\Transfer\ChartDataTraceTransfer;
use Generated\Shared\Transfer\ChartDataTransfer;
use Generated\Shared\Transfer\ChartLayoutTransfer;
use Spryker\Shared\Chart\ChartConfig;
use Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ChartOrder\Communication\ChartOrderCommunicationFactory getFactory()
 * @method \Spryker\Zed\ChartOrder\Business\ChartOrderFacadeInterface getFacade()
 */
class StatusOrderChartPlugin extends AbstractPlugin implements ChartPluginInterface
{
    const NAME = 'status-orders';
    const TITLE = 'Status orders statistic';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
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
    protected function getChartDataTraceTransfer()
    {
        $trace = new ChartDataTraceTransfer();
        $trace->setType(ChartConfig::CHART_TYPE_PIE);
        $trace->setLabels([1, 2, 3]);
        $trace->setValues([1, 2, 3]);

        return $trace;
    }
}
