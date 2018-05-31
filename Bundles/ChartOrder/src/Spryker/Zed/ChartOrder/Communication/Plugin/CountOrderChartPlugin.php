<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ChartOrder\Communication\Plugin;

use Generated\Shared\Transfer\ChartDataTransfer;
use Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ChartOrder\Communication\ChartOrderCommunicationFactory getFactory()
 * @method \Spryker\Zed\ChartOrder\Business\ChartOrderFacadeInterface getFacade()
 */
class CountOrderChartPlugin extends AbstractPlugin implements ChartPluginInterface
{
    const NAME = 'count-orders';

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
        if ($dataIdentifier === null) {
            return new ChartDataTransfer();
        }

        return new ChartDataTransfer();
    }
}
